<?php
namespace TourTracker\Model\DomainObject;
class Departure{

    private $id;
    private $tourId;
    private $startDate;
    private $endDate;
    private $watch;
    private $createdAt;

    public function __construct(){
        $this->createdAt = date("Y-m-d H:i:s");
        $this->watch = 0;
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getTourId(){
        return $this->tourId;
    }

    public function setTourId($tourId){
        $this->tourId = $tourId;
    }

    public function getStartDate(){
        return $this->startDate;
    }

    public function setStartDate($startDate){
        $this->startDate = $startDate;
    }

    public function getEndDate(){
        return $this->endDate;
    }

    public function setEndDate($endDate){
        $this->endDate = $endDate;
    }

    public function getWatch(){
        return $this->watch;
    }

    public function setWatch($watch){
        $this->watch = $watch;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }


}
