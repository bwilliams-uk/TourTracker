<?php
/*
Purpose:
To produce an Array of Departure IDs which meet the requested criteria.
*/

namespace TourTracker\Model\Index;
class DepartureIndex extends Index{

    private $filterVariables = array(
        'tourId',
        'startDate',
        'endDate',
        'available'
    );

    //Returns All departure Ids
    public function all(){
        return $this->processStatement();
    }


    //Returns departures by natural identifiers
    public function byNaturalId($tourId,$startDate,$endDate){
        $this->bindValue(":tourId",$tourId);
        $this->bindValue(":startDate",$startDate);
        $this->bindValue(":endDate",$endDate);
        return $this->processStatement();
    }

    public function tourIdEquals($id){
        $this->bindValue(":tourId",$id);
        return $this->processStatement();
    }

    public function availableByTourId($tourId){
        $this->bindValue(":tourId",$tourId);
        $this->removeComment("available");
        return $this->processStatement();
    }

}
