<?php
/* Purpose: A Wrapper for the
Tour Adjoinment Service to simplify
itinerary creation.
If TAS is improved then this class
may not be required in future.
*/

namespace TourTracker\Services\Secondary;

// TODO finish class
class ItineraryFinderService extends Service{

    private $tours;
    private $earliestStartDate;
    private $lastestStartDate;
    private $latestEndDate;
    private $intervalMin = array();
    private $intervalMax = array();
    private $outputType = 'DEPARTURE_OBJECT' ; // alternative 'JSON'

    protected function init(){
        $sl = $this->ServiceLoader;
        $this->TAS = $sl->get('TourAdjoinmentService');
    }

    public function setTours($tourIdArray){}

    public function setEarliestStartDate($dateString){}

    public function setDepartWindow($days){}

    public function setLatestEndDate($dateString){}

    public function addInterval($min,$max){}

    public function getItineraries(){}

    public function validateAll(){}

    public function validateDates(){}

    public function validateIntervals(){}


}
