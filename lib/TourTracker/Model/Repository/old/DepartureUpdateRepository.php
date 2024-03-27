<?php
namespace TourTracker\Model\Repository;
use TourTracker\Model\DomainObject\DepartureUpdate;
use PDO;
use Exception;
class DepartureUpdateRepository {

    public function __construct($pdo){
        $this->pdo = $pdo;
        $this->stmtSelect = $pdo->prepare('SELECT id, departure_id, price, availability, created_at FROM departure_update WHERE id=:id');
        $this->stmtInsert = $pdo->prepare('INSERT INTO departure_update(departure_id, price, availability, created_at) VALUES(:departure_id, :price, :availability, :created_at)');
        $this->stmtUpdate = $pdo->prepare('UPDATE departure_update SET departure_id = :departure_id, price = :price, availability = :availability, created_at = :created_at WHERE id=:id');
        $this->stmtDelete = $pdo->prepare('DELETE FROM departure_update WHERE id=:id');
    }
    public function get(int $id){
        $stmt = $this->stmtSelect;
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        if(!$result) return false;
        $object = new DepartureUpdate();
        $object->setId($result->id);
        $object->setDepartureId($result->departure_id);
        $object->setPrice($result->price);
        $object->setAvailability($result->availability);
        $object->setCreatedAt($result->created_at);
        return $object;
    }
    public function save(DepartureUpdate $object){
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
        else if($mixed instanceof DepartureUpdate){
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
    public function create(DepartureUpdate $object){
        $stmt = $this->stmtInsert;
        $stmt->bindValue(":departure_id", $object->getDepartureId());
        $stmt->bindValue(":price", $object->getPrice());
        $stmt->bindValue(":availability", $object->getAvailability());
        $stmt->bindValue(":created_at", $object->getCreatedAt());
        $success = $stmt->execute();
        if(!$success) return false;
        $id = $this->pdo->lastInsertId();
        $object->setId($id);
        return true;
    }
    public function update(DepartureUpdate $object){
        $stmt = $this->stmtUpdate;
        $stmt->bindValue(":id", $object->getId());
        $stmt->bindValue(":departure_id", $object->getDepartureId());
        $stmt->bindValue(":price", $object->getPrice());
        $stmt->bindValue(":availability", $object->getAvailability());
        $stmt->bindValue(":created_at", $object->getCreatedAt());
        return $stmt->execute();
    }

}
