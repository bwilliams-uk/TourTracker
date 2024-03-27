<?php
/*
Purpose: To provide base functionality to Index Classes.
*/

namespace TourTracker\Model\Index;
use ReflectionClass;
use TourTracker\Utilities\SqlAdapt;
use PDO;
use Benchmarker\Benchmarker;

class Index {
    protected $pdo;
    private $sqlAdapt;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
        $rc = new ReflectionClass($this);
        $className = $rc->getShortName();
        $sql = __DIR__.'/sql/'.$className.'.sql';
        $this->sqlAdapt = new SqlAdapt($pdo,$sql);
    }


    /*
    public function createFilter(){
        $filter = array();
        foreach($this->variables as $v){
            $filter[$v] = null;
        }
        return $filter;
    }

    public function find($filter = null){
        $filter = $filter ?? $this->createFilter();
        $stmt = $this->getPreparedStatement();
        foreach($filter as $key=>$value){
            $stmt->bindValue(":$key",$value);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN,0);
    }
    */


    //Return Array of IDs matching statement
    protected function processStatement(){
        $this->sqlAdapt->toString();
        $stmt = $this->sqlAdapt->stmt();
        $t = Benchmarker::createTimer("stmt");
        $success = $stmt->execute();
        $t->close();
        if(!$success) return false;
        return $stmt->fetchAll(PDO::FETCH_COLUMN,0);
    }

    //Bind a value to the SQL Statement
    protected function bindValue($label,$value){
        return $this->sqlAdapt->bindValue($label,$value);
    }

    protected function removeComment($label){
        $this->sqlAdapt->removeComment($label);
    }

    //Removes all parameters/filters from SQL statement
    public function reset(){
        $this->sqlAdapt->reset();
    }

}
