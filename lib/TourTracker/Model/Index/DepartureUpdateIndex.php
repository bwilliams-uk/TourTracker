<?php
/*
Purpose:
To produce an Array of DepartureUpdate IDs which meet the requested criteria.
*/

namespace TourTracker\Model\Index;
class DepartureUpdateIndex extends Index{

    private $filterVariables = array(
        'departureId',
        'tourId',
        'latest'
    );


    //Returns All tour Ids
    public function all(){
        return $this->processStatement();
    }

    public function latest(){
        $this->removeComment("latest");
        return $this->processStatement();
    }

    //Returns Tour IDs by URL.
    public function departureIdEquals($id){
        $this->bindValue(":departureId",$id);
        return $this->processStatement();
    }

     public function tourIdEquals($id){
        $this->bindValue(":tourId",$id);
        return $this->processStatement();
    }

}
