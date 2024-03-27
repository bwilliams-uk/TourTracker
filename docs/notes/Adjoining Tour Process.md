<?php

/*
$tourIds = array(int) Ids of the tours you want to do sequentially.
$intervals = array(array(int)) Days between each tour. An array of length count($tourIds)-1, each with two values (min,max)
$timeConstraints = array(4) earliest start, latest start, earliest end, latest end
*/
function getItinenaries($tourIds,$intervals,$timeConstraints){
    // Validation 
    validateIntervals($intervals,$tourIds);    
    //Get Departure Objects
    foreach($tourIds as $tourId){
    $departures[] = getAvailableDepartures($tourId); 
    }
    //Remove Departures which violate timeConstraints
    removeBadDates($departures,$timeConstraints);
    //Create Itinernaries array
    $itineraries = array();

    foreach($departures as $tourDepartures){
    $itineraries  = addTour($itineraries,$tourDepartures,$intervals);
    }
    return $itineraries; //multidimensional array( array($departure,$departure2,$departure3) )
}


/*Purpose: to get the resulting itineries from adding
a tour, differentiates between the first and sequential tour */
function addTour($itineraries,$tourDepartures,$intervals){
    if(empty($itineraries)){
        return itinsCreateFromFirstTour($tourDepartures);
    }
    $preceedingTours = count($intineraries[0]);
    return itinsCreateSequntial($itineraries,$tourDepartures,$intervals[$preceedingTours-1]);
    
}

// Creates itinerary when the tour is the first to be added.
function itinsCreateFromFirstTour($tourDepartures){
    $itins = array();
    foreach ($tourDepartures as $dep)
    {
        $itins[] = array($dep);
    }
    return $itins;
}

// Creates itineraries when  not the first tour.
function itinsCreateSequential($itins,$departures,$intervals){
    $newItins = array();
    foreach ($departures as $dep)
    {
      $newItins = array_merge($newItins,pushDepartureToItins($itins,$dep,$intervals));
    }
    return $newItins;
}

/* Purpose to add the current departure to the itinerary
if it meets the interval requirements, otherwise unset the itinerary.
*/
function pushDepartureToItins($itins,$dep,$intervals){
    $newItins = array();
    $min = $intervals[0];
    $max = $intervals[1];
    foreach($itins as $i){
        $preceedingDeparture = end($itins);
        $fin = $preceedingDeparture->getEndDate();
        $start = $dep->getStartDate();
        $difference = daysDifference($fin,$start);
        if(($difference >= $min) && ($difference <= $max)){
            $newItins[] = array_merge($i,$dep)
        }
    }
    return $newItins;
}

function validateIntervals($intervals,$tourIds){
    if(count($intervals) !== count($tourIds)-1){
        return false;
    }
    foreach($intervals as $i){
        if(count($i) !== 2) return false;
    }
    return true;   
}


function removeBadDates($departures,$timeConstraints){

}





