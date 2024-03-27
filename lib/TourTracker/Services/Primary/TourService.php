<?php
/*
Purpose:
To provide an interface for TourRepository and TourIndex Classes.
*/


namespace TourTracker\Services\Primary;
use TourTracker\Model\DomainObject\Tour;
use Exception;

class TourService extends Service{

    public function tourUrlExists($urlText){
        $index = $this->index;
        $filter = $index->createFilter();
        $filter['url'] = $urlText;
        $matches = $index->find($filter);
        return (count($matches) > 0);
    }

    public function getTourIdsDueSync(){
        $index = $this->index;
        $filter = $index->createFilter();
        $filter['syncDue'] = 1;
        return $index->find($filter);
    }

    public function createTour($name,$url,$operatorId){
        $tour = new Tour();
        $tour->setName($name);
        $tour->setOperatorId($operatorId);
        $tour->setUrl($url);
        $this->repository->save($tour);
    }
}
