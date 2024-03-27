<?php
namespace TourTracker\Reports;
use PDOStatement;

class DepartureReport extends Report{

    public function filterByTour(int $id){
        $this->bindValue(":tourId",$id);
    }
    public function filterWatched(){
        $this->removeComment("watch");
    }

}
