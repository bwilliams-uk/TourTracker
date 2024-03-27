<?php
namespace TourTracker\Services\Secondary;
use TourTracker\Services\ServiceLoader;

abstract class BaseService{
    protected $ServiceLoader;

    public function __construct(ServiceLoader $ServiceLoader){
        $this->ServiceLoader = $ServiceLoader;
        if(method_exists($this,'init') && is_callable(array($this,'init'))){
            $this->init();
        }
    }

}
