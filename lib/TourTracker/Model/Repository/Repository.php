<?php
namespace TourTracker\Model\Repository;
use PDO;
use PDOStatement;
use Exception;
use Benchmarker\Benchmarker;

class Repository{

    const TABLE_PREFIX = '';
    const DOMAIN_OBJECT_NAMESPACE = 'TourTracker\\Model\\DomainObject';

    private $pdo;
    private $statements;
    private $cache = array();

    // Abstract properites - set in child class.
    protected $tableName = "";
    protected $columnNames = array();

    public function __construct(PDO $pdo){

        $this->pdo = $pdo;

        //Prepare basic CRUD Statements
        $statements = array();
        $statements["INSERT"] = $pdo->prepare($this->createInsertStatementSql());
        $statements["DELETE"] = $pdo->prepare($this->createDeleteStatementSql());
        $statements["SELECT"] = $pdo->prepare($this->createSelectStatementSql());
        $statements["UPDATE"] = $pdo->prepare($this->createUpdateStatementSql());
        $this->statements = $statements;
    }

    public function get($identifiers){
        if(is_array($identifiers)){
            return $this->getMultiple($identifiers);
        }
        else{
            return $this->getOne($identifiers);
        }
    }

    public function save($obj){
        $class = $this->getDomainObjectClassName();
        if(!$obj instanceof $class){
            throw new Exception("Object of incorrect type.");
        }
        $columnNames = $this->columnNames;
        $getIdMethod = $this->getGetterMethodName($columnNames[0]);
        $idValue = call_user_func(array($obj,$getIdMethod));
        if(is_int($idValue)){
            $stmt = $this->statements["UPDATE"];
        }
        else{
            $stmt = $this->statements["INSERT"];
            $columnNames = array_slice($columnNames,1);
        }
        foreach($columnNames as $c){
            $label = ':'.$c;
            $getMethod = $this->getGetterMethodName($c);
            $value = call_user_func(array($obj,$getMethod));
            $stmt->bindValue($label,$value);
        }
        $stmt->execute();
    }

    public function remove($identifiers){
        if(is_array($identifiers)){
            return $this->removeMultiple($identifiers);
        }
        else{
            return $this->removeOne($identifiers);
        }
    }

    public function filter(array $criteria = array()){
        //throw new Exception ("Not yet implemented");
        $sql = $this->createFilterStatementSql($criteria);
        $stmt = $this->pdo->prepare($sql);
        $this->bindFilterCriteriaToStatement($stmt,$criteria);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_OBJ);
        if($results){
            return $this->createDomainObjectsFromMultipleResults($results);
        }
        else{
            return false;
        }
    }

    private function createFilterStatementSQL($criteria){
        $tableName = $this->getTableName();
        $columnNames = $this->columnNames;

        $columns = implode(', ',$columnNames);
        $column = $columnNames[0]; // ID Column
        $value = ':'.$column;
        $sql = "SELECT $columns FROM $tableName ";
        $sql .= $this->createFilterWhereClause($criteria);
        return $sql;

    }

    private function createFilterWhereClause($criteria){
        if(count($criteria) === 0){
            return '';
        }
        $wheres = [];
        foreach(array_keys($criteria) as $c){
            $wheres[] =  $this->camelToSnake($c).' = :'.$c;
        }
        $wheres = implode(' AND ',$wheres);
        return 'WHERE '.$wheres;
    }

    private function bindFilterCriteriaToStatement(PDOStatement $statement,$criteria){
        foreach($criteria as $k=>$v){
                $statement->bindValue(':'.$k,$v);
        }
    }

    private function getMultiple($ids){
        $result = array();
        foreach($ids as $id){
            $result[] = $this->getOne($id);
        }
        return $result;
    }

    private function getOne($id){
        if(isset($this->cache[$id])){
            return $this->cache[$id];
        }
        $columnNames = $this->columnNames;
        $label = ':'.$columnNames[0];
        $stmt = $this->statements["SELECT"];
        $stmt->bindValue($label,$id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        if($result){
            return $this->createDomainObjectFromResult($result);
        }
        else{
            return false;
        }
    }

    private function removeMultiple($identifiers){
        foreach($identifiers as $i){
            $this->removeOne($i);
        }
    }

    private function removeOne($mixed){
        $class = $this->getDomainObjectClassName();
        $columnNames = $this->columnNames;

        if(is_int($mixed)){
            $id = $mixed;
        }
        elseif(is_numeric($mixed)){
            $id = intval($mixed);
        }
        elseif($mixed instanceof $class){
            $getIdMethod = $this->getGetterMethodName($columnNames[0]);
            $id = call_user_func(array($mixed,$getIdMethod));
        }
        else{
            throw new Exception("Unknown type supplied to remove method.");
        }

        $stmt = $this->statements["DELETE"];
        $label = ':'.$columnNames[0];
        $stmt->bindValue($label,$id);
        $stmt->execute();
    }


    private function createInsertStatementSql(){
        $tableName = $this->getTableName();
        $columnNames = $this->columnNames;

        $columns = array_slice($columnNames,1); //remove ID
        $columnPlaceholders = $this->arrayPrefix($columns,':');
        $columns = implode(', ',$columns);
        $values = implode(', ',$columnPlaceholders);
        $sql = "INSERT INTO $tableName($columns) VALUES($values)";
        return $sql;
    }

    private function createDeleteStatementSql(){
        $tableName = $this->getTableName();
        $columnNames = $this->columnNames;

        $column = $columnNames[0]; // ID Column
        $value = ':'.$column;
        $sql = "DELETE FROM $tableName WHERE $column = $value LIMIT 1";
        return $sql;
    }

    private function createSelectStatementSql(){
        $tableName = $this->getTableName();
        $columnNames = $this->columnNames;

        $columns = implode(', ',$columnNames);
        $column = $columnNames[0]; // ID Column
        $value = ':'.$column;
        $sql = "SELECT $columns FROM $tableName WHERE $column = $value LIMIT 1";
        return $sql;
    }

    private function createUpdateStatementSql(){
        $tableName = $this->getTableName();
        $columnNames = $this->columnNames;

        $column = $columnNames[0]; // ID Column
        $value = ':'.$column;
        $assignment = array();
        $columns = array_slice($columnNames,1);
        foreach($columns as $c){
            $assignment[] = "$c = :$c";
        }
        $assignment = implode(', ',$assignment);
        $sql = "UPDATE $tableName SET $assignment WHERE $column = $value LIMIT 1";
        return $sql;
    }

    private function getTableName(){
        return self::TABLE_PREFIX.$this->tableName;
    }

    /*Retrieves the Entity part of the Repository Class name
    i.e. for 'UserRepository' returns 'User' */
    private function getEntityName(){
        $fullClassName = get_class($this);
        $pieces = explode('\\',$fullClassName);
        $shortClassName = end($pieces);
        $repository = 'Repository';
        $lastPostion = strrpos($shortClassName,$repository);
        return substr_replace($shortClassName,'',$lastPostion);
    }

    private function createDomainObjectFromResult($result){
        if(!$this->assertDomainObjectClassExists()){
            throw new Exception("Domain Object Class does not exist");
        }
        $class = $this->getDomainObjectClassName();
        $obj = new $class();
        foreach($result as $property=>$value){
            $methodName = $this->getSetterMethodName($property);
            $callable = array($obj,$methodName);
            call_user_func($callable,$value);
        }
        $getIdMethodName = $this->getGetterMethodName($this->columnNames[0]);
        $id = call_user_func(array($obj,$getIdMethodName));
        $this->cache[$id] = $obj;
        return $obj;
    }

    private function createDomainObjectsFromMultipleResults($results){
        $array = [];
        foreach($results as $r){
            $array[] = $this->createDomainObjectFromResult($r);
        }
        return $array;
    }

    private function assertDomainObjectClassExists(){
        $class = $this->getDomainObjectClassName();
        return class_exists($class);
    }
    private function getDomainObjectClassName(){
        return self::DOMAIN_OBJECT_NAMESPACE.'\\'.$this->getEntityName();

    }

    private function getSetterMethodName($property){
        return 'set'.$this->snakeToPascal($property);
    }

    private function getGetterMethodName($property){
        return 'get'.$this->snakeToPascal($property);
    }

    // TODO move to general functions file
    private function snakeToPascal($string){
        $pieces = explode('_',strtolower($string));
        $pieces = array_map('ucfirst',$pieces);
        return implode('',$pieces);
    }

    private function camelToSnake($string){
        $capitals = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        for ($i = 0; $i < 26; $i++)
        {
            $string = str_replace($capitals[$i],'_'.$capitals[$i],$string);
        }
        $string = strtolower($string);
        $string = trim($string,'_');
        return $string;
    }

    // TODO move to general functions file
    private function arrayPrefix($array,$prefix){
        $newArray = array();
        foreach($array as $element){
            $newArray[] = $prefix.$element;
        }
        return $newArray;
    }

}
