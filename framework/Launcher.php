<?php
/* Purpose:
To call the requested Routes Controller Method if it exists, otherwise throw an Exception.
*/

class Launcher{

    const HTTP = 0;
    const CRON = 1;
    private $controllerNamespace = config::CONTROLLER_NAMESPACE;

    public function __construct($mode){
        $this->mode = $mode; //Set as either HTTP or CRON constant.
    }

    public function launch(Route $route){
        //Get information from Route Object
        $controller = $route->getController();
        $method = $route->getMethod();
        $args = $route->getArgs();

        // Define the controller class name
        $controller = $this->controllerNamespace.'\\'.$controller;

        //Controller Validation
        $this->ensureControllerClassExists($controller);
        $this->ensureControllerEnabled($controller);
        $controller = new $controller();
        $this->ensureControllerMethodExists($controller,$method);

        //Finally Call the controller
        call_user_func_array(array($controller,$method),$args);
    }

    private function ensureControllerClassExists($controller){
        if(!class_exists($controller))
        {
            throw new InvalidRouteException();
        }
    }

    private function ensureControllerMethodExists($controller,$method){
        if(!method_exists($controller,$method) || !is_callable(array($controller,$method)))
        {
            throw new InvalidRouteException();
        }
    }

    private function ensureControllerEnabled($controller){
            if($this->mode === self::HTTP){
            $this->ensureControllerHttpEnabled($controller);
        }
        elseif($this->mode === self::CRON){
            $this->ensureControllerCronEnabled($controller);
        }
    }

    private function ensureControllerHttpEnabled($controller){
        if($this->getControllerConstantValue($controller,"DISABLE_HTTP") === true){
            throw new ControllerDisabledException();
        }
    }

    private function ensureControllerCronEnabled($controller){
        if($this->getControllerConstantValue($controller,"DISABLE_CRON") === true){
            throw new ControllerDisabledException();
        }
    }

    private function getControllerConstantValue($controller,$constantName){
        if(defined("$controller::$constantName")){
            return constant("$controller::$constantName");
        }
        else{
            return null;
        }
    }
}

class InvalidRouteException extends Exception{} // Thrown if the controller/method is not found.
class ControllerDisabledException extends Exception{} //Thrown if controller is set to Disabled.
