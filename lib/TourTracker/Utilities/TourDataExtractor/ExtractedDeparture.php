<?php
// Purpose: to hold data regarding a single departure extracted from an operators website.

namespace TourTracker\Utilities\TourDataExtractor;

class ExtractedDeparture{

    private $startDate;
    private $endDate;
    private $price;
    private $availability;

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

}
