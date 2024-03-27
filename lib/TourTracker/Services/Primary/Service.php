<?php
namespace TourTracker\Services\Primary;

abstract class Service{
    protected $repository;
    protected $index;

    public function __construct($repository,$index){
        $this->repository = $repository;
        $this->index = $index;
        if(method_exists($this,'init') && is_callable(array($this,'init'))){
            $this->init();
        }
    }

    public function getById($id){
        return $this->repository->get($id);
    }

    public function deleteById($id){
        $this->repository->remove($id);
    }
}
