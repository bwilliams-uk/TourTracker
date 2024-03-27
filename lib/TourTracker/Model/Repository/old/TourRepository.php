<?php
namespace TourTracker\Model\Repository;
use TourTracker\Model\DomainObject\Tour;
use PDO;
use Exception;
class TourRepository {

    public function __construct($pdo){
        $this->pdo = $pdo;
        $this->stmtSelect = $pdo->prepare('SELECT id, name, operator_id, url, created_at FROM tour WHERE id=:id');
        $this->stmtInsert = $pdo->prepare('INSERT INTO tour(name, operator_id, url, created_at) VALUES(:name, :operator_id, :url, :created_at)');
        $this->stmtUpdate = $pdo->prepare('UPDATE tour SET name = :name, operator_id = :operator_id, url = :url, created_at = :created_at WHERE id=:id');
        $this->stmtDelete = $pdo->prepare('DELETE FROM tour WHERE id=:id');
    }
    public function get(int $id){
        $stmt = $this->stmtSelect;
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        if(!$result) return false;
        $object = new Tour();
        $object->setId($result->id);
        $object->setName($result->name);
        $object->setOperatorId($result->operator_id);
        $object->setUrl($result->url);
        $object->setCreatedAt($result->created_at);
        return $object;
    }
    public function save(Tour $object){
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
        else if($mixed instanceof Tour){
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
    public function create(Tour $object){
        $stmt = $this->stmtInsert;
        $stmt->bindValue(":name", $object->getName());
        $stmt->bindValue(":operator_id", $object->getOperatorId());
        $stmt->bindValue(":url", $object->getUrl());
        $stmt->bindValue(":created_at", $object->getCreatedAt());
        $success = $stmt->execute();
        if(!$success) return false;
        $id = $this->pdo->lastInsertId();
        $object->setId($id);
        return true;
    }
    public function update(Tour $object){
        $stmt = $this->stmtUpdate;
        $stmt->bindValue(":id", $object->getId());
        $stmt->bindValue(":name", $object->getName());
        $stmt->bindValue(":operator_id", $object->getOperatorId());
        $stmt->bindValue(":url", $object->getUrl());
        $stmt->bindValue(":created_at", $object->getCreatedAt());
        return $stmt->execute();
    }

}
