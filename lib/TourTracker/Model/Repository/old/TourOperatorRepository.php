<?php
namespace TourTracker\Model\Repository;
use TourTracker\Model\DomainObject\TourOperator;
use PDO;
use Exception;
class TourOperatorRepository {

    public function __construct($pdo){
        $this->pdo = $pdo;
        $this->stmtSelect = $pdo->prepare('SELECT id, name, web, supported FROM tour_operator WHERE id=:id');
        $this->stmtInsert = $pdo->prepare('INSERT INTO tour_operator(name, web, supported) VALUES(:name, :web, :supported)');
        $this->stmtUpdate = $pdo->prepare('UPDATE tour_operator SET name = :name, web = :web, supported = :supported WHERE id=:id');
        $this->stmtDelete = $pdo->prepare('DELETE FROM tour_operator WHERE id=:id');
    }
    public function get(int $id){
        $stmt = $this->stmtSelect;
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        if(!$result) return false;
        $object = new TourOperator();
        $object->setId($result->id);
        $object->setName($result->name);
        $object->setWeb($result->web);
        $object->setSupported($result->supported);
        return $object;
    }
    public function save(TourOperator $object){
        if(is_int($object->getId())){
            return $this->update($object);
        }
        else{
            return $this->create($object);
        }
    }
    public function remove($mixed){
        if(is_int($mixed)){
            $id = $mixed;
        }
        else if($mixed instanceof TourOperator){
            $id = $mixed->getId();
            if(!is_int($id)) throw new Exception("ID value not an integer.");
        }
        else{
            throw new Exception("Invalid value passed to remove method.");
        }
        $stmt = $this->stmtDelete;
        $stmt->bindValue(":id",$id);
        return $stmt->execute();
    }
    public function create(TourOperator $object){
        $stmt = $this->stmtInsert;
        $stmt->bindValue(":name", $object->getName());
        $stmt->bindValue(":web", $object->getWeb());
        $stmt->bindValue(":supported", $object->getSupported());
        $success = $stmt->execute();
        if(!$success) return false;
        $id = $this->pdo->lastInsertId();
        $object->setId($id);
        return true;
    }
    public function update(TourOperator $object){
        $stmt = $this->stmtUpdate;
        $stmt->bindValue(":id", $object->getId());
        $stmt->bindValue(":name", $object->getName());
        $stmt->bindValue(":web", $object->getWeb());
        $stmt->bindValue(":supported", $object->getSupported());
        return $stmt->execute();
    }

}
