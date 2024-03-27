<?php
namespace TourTracker\Model\DomainObject;
class DepartureUpdate{

    private $id;
    private $departureId;
    private $price;
    private $availability;
    private $createdAt;

    public function __construct(){
        $this->createdAt = date("Y-m-d H:i:s");
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getDepartureId(){
        return $this->departureId;
    }

    public function setDepartureId($departureId){
        $this->departureId = $departureId;
    }

    public function getPrice(){
        return $this->price;
    }

    public function setPrice($price){
        $this->price = $price;
    }

    public function getAvailability(){
        return $this->availability;
    }

    public function setAvailability($availability){
        $this->availability = $availability;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }

    public function age(){
        $date1 = date_create($this->getCreatedAt());
        $date2 = date_create();
        $diff = date_diff($date1,$date2);
        return intval($diff->format("%a"));
    }


}
