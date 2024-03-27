<?php
namespace TourTracker\Reports;
use TourTracker\Utilities\SqlAdapt;
use PDO;
use PDOStatement;

abstract class Report{

    private $pdo;
    private $sqlAdapt;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;

        //Obtain child class name.
        $fullClassName = get_class($this);
        $pieces = explode('\\',$fullClassName);
        $className = end($pieces);

        //Make path name for SQL file
        $sql = __DIR__.'/sql/'.$className.'.sql';
        $this->sqlAdapt = new SqlAdapt($pdo,$sql);

        //Call INIT method on child class
        if(method_exists($this,'init') && is_callable(array($this,'init'))){
            $this->init();
        }
    }

    // Outputs the result set as JSON data
    public function printJson(){
        header("content-type:application/json");
        $data = $this->getData();
        echo json_encode($data,JSON_PRETTY_PRINT);
    }

    // Return the query result set
    private function getData(){
        $stmt = $this->sqlAdapt->stmt();
        // die($this->sqlAdapt->toString());
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    //Bind Value, proxy for sqlAdapt class
    protected function bindValue($label,$value){
        return $this->sqlAdapt->bindValue($label,$value);
    }

    //Remove Comment, proxy for sqlAdapt class
    protected function removeComment($label){
        $this->sqlAdapt->removeComment($label);
    }

    //Removes all parameters/filters from SQL statement
    public function reset(){
        $this->sqlAdapt->reset();
    }



}

