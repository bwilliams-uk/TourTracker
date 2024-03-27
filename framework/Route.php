<?php

class Route{
    private $routeData;
    private $defaultMethod = config::DEFAULT_CONTROLLER_METHOD;

    public function __construct($route){
        $route = trim($route,"/ \\");
        $route = $this->applyRewriteRules($route);
        $segments = explode("/",$route);
        $this->routeData = $segments;
    }
    public function getController(){
        return $this->routeData[0];
    }
    public function getMethod(){
        return $this->routeData[1] ?? $this->defaultMethod;
    }
    public function getArgs(){
        return array_slice($this->routeData,2) ?? array();
    }

    private function applyRewriteRules($route){
        foreach(config::REWRITE_RULES as $from=>$to){
            $rw = new RewriteRule($from,$to);
            $route = $rw->rewrite($route);
        }
        return $route;
    }

}
