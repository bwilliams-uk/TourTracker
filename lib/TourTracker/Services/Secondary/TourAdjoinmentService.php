<?php
/*
Purpose:
This service is used to identify departures
that can be "chained" together to create a
longer itinerary.
*/


namespace TourTracker\Services\Secondary;
use TourTracker\Model\DomainObject\Tour;
use Benchmarker\Benchmarker;
use StdClass;


class TourAdjoinmentService extends Service{

    private $tourIds;
    private $log = ""; // contains log data for debugging.

    protected function init(){
        //$this->TourService = $this->ServiceLoader->get('TourService');
        $this->DepartureService = $this->ServiceLoader->get('DepartureService');
        $this->DepartureUpdateService = $this->ServiceLoader->get('DepartureUpdateService');
    }



    /**
    This is the primary function for identifying potential itineraries.
    Inputs:
    $tourIds - An array of tour IDs in the order they will be attended.
    $intervals - The min/max days between tours, in the order the intervals occur.
                 Assuming 3 tours then: array( array(min1,max1) , array(min2,max2) )
                 Where min1/max1 represent min/max days between tour 1 and tour 2.

    $timeConstraints - array. Dates in yyyy-mm-dd format where [0] earliest start date,
                       [1] latest end date. TODO: [2] latest start date, [3] earliest end date.

    */
    public function getItineraries($tourIds,$intervals,$timeConstraints){

        // Validation
        $result = $this->validateIntervals($intervals,$tourIds);
        if(!$result) throw new Exception("Intervals are invalid.");


        //Get Departure Objects
        foreach($tourIds as $tourId){
        $departures[] = $this->DepartureService->getAvailableDepartures($tourId);
        }



        //Remove Departures which violate timeConstraints - FUTURE Filter in MySQL db.
        $departures = $this->removeBadDates($departures,$timeConstraints);

        //Create Itinernaries array
        $itineraries = array();

        foreach($departures as $tourDepartures){
        $itineraries  = $this->addTour($itineraries,$tourDepartures,$intervals);
        }
        return $itineraries; //multidimensional array( array($departure,$departure2,$departure3) )
    }

    //Formats the result of this->getItineraries() as JSON.
    // FUTURE have a setOutputFormat() method then just call getItineraries per usual.
    public function getItinerariesAsJson(){
        $args = func_get_args();
        $result = $this->getItineraries(...$args);
        $itins = array();
        foreach($result as $r){
            $depIds = array();
            foreach($r as $departure){
                $id = $departure->getId();
                $depIds[] = $id;
            }
            $itins[] = $depIds;
        }
        return $itins;
    }


    private function formatItinerary($intinerary){
        $itin = new StdClass();
        $itin->departures = array();
        foreach($itinerary as $departure){
            $itin->departures[] = $this->formatDeparture($departure);
        }
        return $itin;
    }

    private function formatDeparture($departure){
        $dep = new StdClass();
    }


    /**
        Checks that the number of intervals is 1 less than the number
        of tours.
        Also that each interval has a length of 2 representing minimum
        and maximum values.

    */
    public function validateIntervals($intervals,$tourIds){
        if(count($intervals) !== count($tourIds)-1){
            return false; // disagreement between number of tours vs number of intervals.
        }
        foreach($intervals as $i){
            if(count($i) !== 2) return false; // intervals must contain 2 values: min,max
            if($i[0] > $i[1]) return false; // min must not be greater than max.
        }
        return true;
    }



    private function removeBadDates($departures,$timeConstraints){
        $cleaned = array();
        $i = 0;
        foreach($departures as $tourDepartures){
            $temp = $tourDepartures;
            if(isset($timeConstraints[0])){
                $temp = $this->removeStartDatesBefore($timeConstraints[0],$temp);
            }
            else{
                $temp = $tourDepartures;
            }
            if(isset($timeConstraints[1])){
                $temp = $this->removeEndDatesAfter($timeConstraints[1],$temp);
            }

            //Remove start dates after the latest departure date from first tour only.
            if( ($i==0) && isset($timeConstraints[2])){
                $temp = $this->removeStartDatesAfter($timeConstraints[2],$temp);
            }
            $cleaned[] = $temp;
            $i++;
        }
        return $cleaned;
    }

    private function removeStartDatesBefore($date,$departures){
        $cleaned = array();
        foreach($departures as $d){
            if($d->getStartDate() >= $date){
                $cleaned[] = $d;
            }
        }
        return $cleaned;
    }

    private function removeStartDatesAfter($date,$departures){
        $cleaned = array();
        foreach($departures as $d){
            if($d->getStartDate() <= $date){
                $cleaned[] = $d;
            }
        }
        return $cleaned;
    }

    private function removeEndDatesAfter($date,$departures){
        $cleaned = array();
        foreach($departures as $d){
            if($d->getEndDate() <= $date){
                $cleaned[] = $d;
            }
        }
        return $cleaned;
    }



        /*Purpose: to get the resulting itineries from adding
    a tour, differentiates between the first and sequential tour */
    private function addTour($itineraries,$tourDepartures,$intervals){
        if(empty($itineraries)){
            return $this->itinsCreateFromFirstTour($tourDepartures);
        }
        $preceedingTours = count($itineraries[0]);
        $return = $this->itinsCreateSequential($itineraries,$tourDepartures,$intervals[$preceedingTours-1]);
        return $return;
    }
    // Creates itinerary when the tour is the first to be added.
    private function itinsCreateFromFirstTour($tourDepartures){
        $itins = array();
        foreach ($tourDepartures as $dep)
        {
            $itins[] = array($dep);
        }
        return $itins;
    }

    // Creates itineraries when  not the first tour.
    private function itinsCreateSequential($itins,$departures,$intervals){
        $newItins = array();
        foreach ($departures as $dep)
        {
          $newItins = array_merge($newItins,$this->pushDepartureToItins($itins,$dep,$intervals));
        }
        return $newItins;
    }

    /* Purpose to add the current departure to the itinerary
    if it meets the interval requirements, otherwise unset the itinerary.
    */
    private function pushDepartureToItins($itins,$dep,$intervals){
        $newItins = array();
        $min = $intervals[0];
        $max = $intervals[1];
        foreach($itins as $i){
            $preceedingDeparture = end($i);
            $fin = date_create($preceedingDeparture->getEndDate());
            $start = date_create($dep->getStartDate());
            $difference = date_diff($fin,$start);
            $difference = intval($difference->format("%r%a"));
            if(($difference >= $min) && ($difference <= $max)){
                array_push($i,$dep);
                $newItins[] = $i;
            }
        }
        //print_r($newItins);
        return $newItins;
    }

    private function log($text){
        $this->log .= $text."\n";
    }

    public function __destruct(){
        //file_put_contents(PROJECT_DIR.'/adjoinLog.txt',$this->log);
    }
}
