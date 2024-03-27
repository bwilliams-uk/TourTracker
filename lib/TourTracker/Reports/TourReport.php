<?php
namespace TourTracker\Reports;
use TourTracker\Config;
use PDOStatement;

class TourReport extends Report{
    protected function init(){
        $this->bindValue(":syncLimit",Config::SYNC_LIMIT);
    }

}
