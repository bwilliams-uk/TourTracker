<?php
/*
Purpose: To provide base functionality to Index Classes.
*/

namespace TourTracker\Model\Index;
use PDO;
use Benchmarker\Benchmarker;

class Index {
    private $pdo;
    private $statement;

    protected $idColumnIndex = 0;
    protected $defaultBindings = array();
    protected $filterVariables = array();

    //Find the SQL file and make a prepared statement.
    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
        $className = get_class($this);
        $classPieces = explode('\\',$className);
        $className = end($classPieces);
        $sqlFile = __DIR__.'/sql/'.$className.'.sql';
        $sql = file_get_contents($sqlFile);
        $this->statement = $pdo->prepare($sql);
        $this->bindAssocArray($this->defaultBindings);
    }

    //Create an array with each filter variable set to a key. default value NULL.
    public function createFilter(){
        $filter = array();
        foreach($this->filterVariables as $v){
            $filter[$v] = null;
        }
        return $filter;
    }
    // Bind filter variables to statement, return IDs as Array
    public function find($filter = null){
        $filter = $filter ?? $this->createFilter();
        if(!$this->validateFilter($filter)){
            throw new Exeption("Index filter is not valid.");
        }
        $this->bindAssocArray($filter);
        $stmt = $this->statement;
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN,$this->idColumnIndex);
    }

    private function bindAssocArray($array){
        $stmt = $this->statement;
        foreach($array as $key=>$value){
            $type = $this->identifyPdoType($value);
            $stmt->bindValue(":$key",$value,$type);
        }
    }

    private function identifyPdoType($value){
        if($value === null){
            return PDO::PARAM_NULL;
        }
        elseif(is_int($value)){
            return PDO::PARAM_INT;
        }
        else{
            return PDO::PARAM_STR;
        }
    }

    private function validateFilter($filter){
        $default = $this->createFilter();
        $keysA = array_keys($default);
        $keysB = array_keys($filter);
        sort($keysA);
        sort($keysB);
        return ($keysA === $keysB);
    }


}
