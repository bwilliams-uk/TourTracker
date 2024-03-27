<?php

class config{

const DEFAULT_ROUTE = "app/main"; //Default page to go to when no path provided
const DEFAULT_CONTROLLER_METHOD = "main";
const CONTROLLER_NAMESPACE = "controller";
const TIMEZONE = "Europe/London";




/*

***************REWRITE RULES***************

Use rewrite rules to change the order of URL parameters.
For example, to reroute "profile/1234/edit" to the default
route for "profile/edit/1234" you can use:

"profile/{#id}/edit" => "profile/edit/{#id}"

The '#' symbol indicates that placeholder contains digits only,
if the profile identifier uses word characters (a-zA-Z0-9_)
you can just use '{id}'.

*******************************************
*/


const REWRITE_RULES = array(
"tour/{#id}/departures" => "report/departures/{#id}",
"departure/{#id}/history" => "report/history/{#id}"
);



/*
Set Controller/Method Routes which are to be by
periodocally running chron.php. */

const CRON_JOBS = array(
"cron/sync"
);

}



?>
