<?php

/*
Purpose:
To provide an API for using the
TourAdjoinmentService.

STATUS: Experimental / In development

*/

namespace controller;

use TourTracker\TourTracker;
use TourTracker\Services\ServiceLoader;
use Exception;
use Benchmarker\Benchmarker;

class itineraries
{
    private $pdo;

    public function __construct()
    {
        $app = new TourTracker();
        $pdo =  $app->createPdo();
        $this->sl = new ServiceLoader($pdo);
    }


    public function find()
    {
        $t1 = Benchmarker::createTimer("MAIN");


        // Load service
        $adj = $this->sl->get("TourAdjoinmentService");
        //$dus = $this->sl->get("DepartureUpdateService");

        // Remove Zeros from Posted IDs
        $ids = array_filter($_POST["tours"],function ($a){return ($a>0);});

        // Create Time constraint Array
        $earliestStart = $_POST['start'];
        $latestEnd = $_POST['end'];
        $latestStart = date_add(date_create($earliestStart),
                                date_interval_create_from_date_string($_POST['window'].' days')
                               );
        $latestStart = date_format($latestStart,"Y-m-d");

        $timeConstraints = array($earliestStart,$latestEnd,$latestStart);

        //Create Interval Array
        $interval = array($_POST["minInterval"],$_POST["maxInterval"]);

        //repeat for nTours - 1. UI does not yet support varying interval lengths.
        $interval = array_fill(0,count($ids)-1,$interval);

        //Create Itineraries
        $result = $adj->getItineraries($ids,$interval,$timeConstraints);


        $itins = array();
        foreach($result as $r){
            $depIds = array();
            foreach($r as $departure){
                $id = $departure->getId();
                $depIds[] = $id;
            }
            $itins[] = $depIds;
        }
        header("content-type:application/json;");
        echo json_encode($itins,JSON_PRETTY_PRINT);

        $t1->close();
        Benchmarker::createReport();
    }


}
