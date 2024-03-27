<?php
namespace TourTracker\Reports;
use PDOStatement;
use Exception;

class SyncHistoryReport extends Report{

    public function filterByDeparture($departureId){
        $this->bindValue(":departureId",$departureId);
    }

}
