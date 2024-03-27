<?php
namespace TourTracker;
use PDO;
class TourTracker extends Config{

    public function createPdo(){
        $dbName = $this->dbName;
        $dbHost = $this->dbHost;
        $dbUser = $this->dbUser;
        $dbPass = $this->dbPass;
        $dsn = "mysql:dbname=$dbName;host=$dbHost";
        return new PDO($dsn,$dbUser,$dbPass);
    }
}
