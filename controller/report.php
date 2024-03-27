<?php

/*
Purpose:
To provide an API for obtaining database
view/table data in JSON format.
*/

namespace controller;

use TourTracker\TourTracker;
use TourTracker\Reports\TourReport;
use TourTracker\Reports\DepartureReport;
use TourTracker\Reports\SyncHistoryReport;
use TourTracker\Reports\AlertReport;

class report
{
    const DISABLED = false;
    private $pdo;

    public function __construct(){
        $app = new TourTracker();
        $this->pdo =  $app->createPdo();
    }


    //Return Tour JSON Data
    public function tours(){
        $report = new TourReport($this->pdo);
        $report->printJson();
    }

    //Return Departure JSON Data
    //URL Rerouted from /tour/{tourId}/departures
    public function departures($tourId = null){
        $watchedOnly = $_GET['watched'] ?? 0;
        $report = new DepartureReport($this->pdo);
        if($tourId) $report->filterByTour($tourId);
        if($watchedOnly) $report->filterWatched();
        $report->printJson();
    }

    //Return Price and Availability JSON Data
    //URL Rerouted from /departure/{departureId}/history
    public function history($departureId = null){
        $report = new SyncHistoryReport($this->pdo);
        if($departureId) $report->filterByDeparture($departureId);
        $report->printJson();
    }

    //Returns JSON data of price/availability changes
    public function alerts(){
        $report = new AlertReport($this->pdo);
        $report->printJson();
    }

}


?>
