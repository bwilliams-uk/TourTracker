<?php

/*
SqlAdapt is a utility for dynamically adjusting SQL
statements based on the parameters given, ideal for
toggling filters etc.
Rather than building a query on-the-go, SqlAdapt
uses comments as switches for toggling clauses in
the query depending whether a binding value is provided.

Example:

---------------------------------------

example.sql

SELECT * FROM Users
WHERE 1=1
-- [:id] AND id = :id
-- [:name] AND name = :name

---------------------------------------

example.php

$sa = new SqlAdapt($pdo,"example.sql");
// $sa->bindValue(":id",$id);
// $sa->bindValue(":name",$name);
$stmt = $sa->stmt();
$stmt->execute();
var_dump($stmt->fetchAll());
$sa->reset();

---------------------------------------
This will output all user rows, however
uncommenting the value bindings in the
php code will automatically uncomment
the respective lines of the SQL query.

$sa->removeComment('label') can also
be used to uncomment lines without
binding values.

e.g.
$sa->removeComment('sortNameDesc');

-- [sortNameDesc] ORDER BY name DESC

Comments can alose be restored:

$sa->restoreComment('sortNameDesc');




*/

namespace TourTracker\Utilities;
use PDO;
use PDOStatement;
class SqlAdapt{
    private $pdo;
    private $sql;
    private $sqlOriginal;
    private $boundValues = array();
    private $removedCounter = 0;
    private $removedStrings = array();
    private $preparedStatement = null;


    public function __construct(PDO $pdo = null,$string){

        if(file_exists($string)){
            $sql = file_get_contents($string);
        }
        else{
            $sql = $string;
        }

        $this->pdo = $pdo;
        $this->sql = $sql;
        $this->sqlOriginal = $sql;
    }

    public function bindValue($label,$value){
        $this->clearPreparedStatementIfNewBinding($label);
        $this->boundValues[$label] = $value;
        $this->removeComment($label);
        // remove comments with label

    }

    public function getPreparedStatement(){
        if($this->preparedStatement instanceof PDOStatement){
            $stmt = $this->preparedStatement;
        }
        else{
            $stmt = $this->pdo->prepare($this->sql);
        }
        foreach($this->boundValues as $key=>$value){
            $stmt->bindValue($key,$value);
        }
        return $stmt;
    }

    public function toString(){
        return $this->sql;
    }

    public function reset(){
        $this->sql = $this->sqlOriginal;
        $this->preparedStatement = null;
        $this->boundValues = array();
        $this->removedCounter = 0;
        $this->removedStrings = array();
    }

    public function removeComment($label){
        //Note: does not currently require REGEX, initial plan was for multi matching e.g.[:a :b]
        preg_match_all("/-- \[".$label."\]/",$this->sql,$matches);
        foreach($matches[0] as $m){
            $this->sql = str_replace($m,$this->getRemovedPlaceholder($m),$this->sql);
        }

    }

    public function restoreComment($label){
        foreach($this->removedStrings as $key=>$value){
            //Note: does not currently require REGEX, initial plan was for multi matching e.g.[:a :b]
            if(preg_match("/-- \[".$label."\]/",$value,$matches)){
                $this->sql = str_replace("/*SqlAdapt:$key*/",$matches[0],$this->sql);
            }
        }

    }
    private function getRemovedPlaceholder($removedContent){
        $placeholder = "/*SqlAdapt:$this->removedCounter*/";
        $this->removedStrings[$this->removedCounter] = $removedContent;
        $this->removedCounter++;
        return $placeholder;
    }

    private function clearPreparedStatementIfNewBinding($label){
        if(!isset($this->boundValues[$label])){
            $this->preparedStatement = null;
        }
    }

    //Aliases
    public function stmt(){
        return $this->getPreparedStatement();
    }



}
