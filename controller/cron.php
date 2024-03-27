<?php
/*
Purpose:
To define the tasks which are run
as cron jobs.
*/

namespace controller;

use TourTracker\TourTracker;
use TourTracker\Services\ServiceLoader;

class cron{

    const DISABLE_HTTP = true; //Prevents access via http route.
    //const DISABLE_CRON = true;

    public function __construct(){
        $app = new TourTracker();
        $pdo = $app->createPdo();
        $sl = new ServiceLoader($pdo);
        $this->TourSyncService = $sl->get("TourSyncService");
    }

    //Syncs one due tour every time a cron job is run.
    public function sync(){
        $this->TourSyncService->syncRandomTour();
    }

}
