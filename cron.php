<?php
/*
Purpose: To run chron jobs
at the specified interval.

Specify which controller methods are cron jobs
in 'framework/config.php'.

*/

require_once("framework/main.php");

//Validate which controllers are due to be called.

$jobs = config::CRON_JOBS;
$launcher = new Launcher(Launcher::CRON);

//Make calls to controller methods specified in config::CRON_JOBS
foreach($jobs as $job){
    $route = new Route($job);
    try{
        $launcher->launch($route);
    }
    catch (InvalidRouteException $e) {
        // do logging here.
    }
    catch (ControllerDisabledException $e){
        // do logging here.
    }
}
