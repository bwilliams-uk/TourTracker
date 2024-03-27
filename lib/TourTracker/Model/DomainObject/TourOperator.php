<?php
namespace TourTracker\Model\DomainObject;
class TourOperator{

    private $id;
    private $name;
    private $web;
    private $supported;

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getWeb(){
        return $this->web;
    }

    public function setWeb($web){
        $this->web = $web;
    }

    public function getSupported(){
        return $this->supported;
    }

    public function setSupported($supported){
        $this->supported = $supported;
    }


}
