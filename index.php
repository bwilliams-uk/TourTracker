<?php
require_once("framework/main.php");

//Initiate the applicaton
$route = $_SERVER["PATH_INFO"] ?? config::DEFAULT_ROUTE;
$route = new Route($route);
try{
$launcher = new Launcher(Launcher::HTTP);
$launcher->launch($route);
}
catch (InvalidRouteException $e) {
    echo "Page not found." ;
}
catch (ControllerDisabledException $e){
    echo "Access to this resource is disabled.";
}

?>
