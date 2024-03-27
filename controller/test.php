<?php

/*

Test routes for use in development/debugging.
Not required for the applications operation.

*/

namespace controller;

use TourTracker\TourTracker;
use TourTracker\Services\ServiceLoader;
use TourTracker\Utilities\TourDataExtractor\TourDataExtractor;
use TourTracker\Utilities\URL;
use TourTracker\View\Json\SuccessResponse;
use Exception;

class test
{
    private $pdo;

    public function __construct()
    {
        $app = new TourTracker();
        $pdo =  $app->createPdo();
        $this->sl = new ServiceLoader($pdo);
    }


    public function extractor()
    {
        header("content-type:text/plain");
        $url = $_GET["url"] ?? null;
        $url = new URL($url);
        $op = $this->sl->get("TourOperatorService");
        $operator = $op->identifyOperatorByUrl($url,true);
        $extractor = new TourDataExtractor($operator,$url);
        echo $extractor->getTourName()."\n";
        var_dump($extractor->getDepartureUpdates(true));

    }

    // Get departures
    public function adjoin(){
        header("content-type:text/plain");
        $ids = array(6,54,51,6); //Tours
        $interval = array(array(1,5),array(1,5),array(1,5)); // make between 1&5 days apart
        $adj = $this->sl->get("TourAdjoinmentService");
        $tc = array("2024-06-01","2024-10-01");
        $result = $adj->getItineraries($ids,$interval,$tc);
        var_dump($result);
    }

    public function depreport(){
        $ds = $this->sl->get("DepartureService");
        $deps = $ds->getAvailableDepartures(6);
        $s = $this->sl->get("DepartureReportingService");
        $arr = array();
        foreach($deps as $d){
        $arr[] = $s->createReport($d);
        }
        header("content-type:application/json");
        echo json_encode($arr,JSON_PRETTY_PRINT);
    }

}
