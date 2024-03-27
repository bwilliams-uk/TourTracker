<?php
/*
Purpose:
To produce an Array of TourOperator IDs which meet the requested criteria.
*/

namespace TourTracker\Model\Index;
use PDO;
class TourOperatorIndex extends Index{

    private $filterVariables = array(
        'web'
    );


    public function all(){
        return $this->processStatement();
    }

    public function webSimilarTo($web){
        $this->bindValue(":web",$web);
        return $this->processStatement();
    }


}
