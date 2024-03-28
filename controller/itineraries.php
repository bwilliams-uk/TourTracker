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

        //Format to get JSON formatting with pricing data. -- REMOVE?
        //$result = $adj->format($result);

        header("content-type:text/plain;");
        echo count($result)." result(s) found.\n\n";
        $i = 1;
        foreach($result as $r){
            $first = $r[0];
            $last = end($r);
            //$cost = 0;
            $depIds = array();
            foreach($r as $departure){
                $id = $departure->getId();
                $depIds[] = $id;
                //$update = $dus->getLatestByDepartureId($id);
                //$cost += $update->getPrice();
            }
            $depIds = implode(', ',$depIds);
            echo $i.".\n First tour starts on ".$first->getStartDate()."\nLast tour ends on ".$last->getEndDate()."\n Departures: ($depIds) \n\n";
            $i++;
        }

        $t1->close();
        Benchmarker::createReport();
    }


}
