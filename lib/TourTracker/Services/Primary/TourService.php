<?php
/*
Purpose:
To provide an interface for TourRepository and TourIndex Classes.
*/


namespace TourTracker\Services\Primary;
use TourTracker\Model\DomainObject\Tour;
use Exception;

class TourService extends BaseService{

    public function tourUrlExists($urlText){
        $matches = $this->index->urlEquals($urlText);
        return (count($matches) > 0);
    }

    public function getTourIdsDueSync(){
        return $this->index->syncDue();
    }

    public function createTour($name,$url,$operatorId){
        $tour = new Tour();
        $tour->setName($name);
        $tour->setOperatorId($operatorId);
        $tour->setUrl($url);
        $this->repository->save($tour);
    }
}
