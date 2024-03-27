<?php
/*
Include file for ../index.php (HTTP Access) and ../cron.php (Cron Job Executer).
*/

define("PROJECT_DIR",dirname(__DIR__)); //Make the root project folder globally accessible.

//Include Framework files

require_once('config.php');
date_default_timezone_set(config::TIMEZONE);

require_once('autoload.php');
require_once("RewriteRule.php");
require_once('Route.php');
require_once('Launcher.php');
?>
