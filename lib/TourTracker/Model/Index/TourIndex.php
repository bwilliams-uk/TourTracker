<?php
/*
Purpose:
To produce an Array of Tour IDs which meet the requested criteria.
*/

namespace TourTracker\Model\Index;
use TourTracker\Config;

class TourIndex extends Index{

    private $filterVariables = array(
        'url'
    );

    //Returns All tour Ids
    public function all(){
        return $this->processStatement();
    }

    //Returns Tour IDs by URL.
    public function urlEquals($url){
        $this->bindValue(":url",$url);
        return $this->processStatement();
    }

    // Return Tour IDs where Sync is due.
    public function syncDue(){
        $this->bindValue(":syncLimit",Config::SYNC_LIMIT);
        return $this->processStatement();
    }

}
