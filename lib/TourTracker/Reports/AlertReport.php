<?php
namespace TourTracker\Reports;
use TourTracker\Config;
use PDOStatement;

class AlertReport extends Report{

    public function init(){
        $this->bindValue(":maxAge",Config::ALERT_MAX_AGE);
        $this->bindValue(":availabilityThreshold",Config::ALERT_AVAILABILITY_THRESHOLD);
    }

}
