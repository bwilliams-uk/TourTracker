<?php
namespace TourTracker\Model\Repository;
use TourTracker\Model\DomainObject\Departure;
use PDO;
use Exception;
class DepartureRepository {

    public function __construct($pdo){
        $this->pdo = $pdo;
        $this->stmtSelect = $pdo->prepare('SELECT id, tour_id, start_date, end_date, watch, created_at FROM departure WHERE id=:id');
        $this->stmtInsert = $pdo->prepare('INSERT INTO departure(tour_id, start_date, end_date, watch, created_at) VALUES(:tour_id, :start_date, :end_date, :watch, :created_at)');
        $this->stmtUpdate = $pdo->prepare('UPDATE departure SET tour_id = :tour_id, start_date = :start_date, end_date = :end_date, watch = :watch, created_at = :created_at WHERE id=:id');
        $this->stmtDelete = $pdo->prepare('DELETE FROM departure WHERE id=:id');
    }
    public function get(int $id){
        $stmt = $this->stmtSelect;
        $stmt->bindValue(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        if(!$result) return false;
        $object = new Departure();
        $object->setId($result->id);
        $object->setTourId($result->tour_id);
        $object->setStartDate($result->start_date);
        $object->setEndDate($result->end_date);
        $object->setWatch($result->watch);
        $object->setCreatedAt($result->created_at);
        return $object;
    }
    public function save(Departure $object){
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
        else if($mixed instanceof Departure){
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
    public function create(Departure $object){
        $stmt = $this->stmtInsert;
        $stmt->bindValue(":tour_id", $object->getTourId());
        $stmt->bindValue(":start_date", $object->getStartDate());
        $stmt->bindValue(":end_date", $object->getEndDate());
        $stmt->bindValue(":watch", $object->getWatch());
        $stmt->bindValue(":created_at", $object->getCreatedAt());
        $success = $stmt->execute();
        if(!$success) return false;
        $id = $this->pdo->lastInsertId();
        $object->setId($id);
        return true;
    }
    public function update(Departure $object){
        $stmt = $this->stmtUpdate;
        $stmt->bindValue(":id", $object->getId());
        $stmt->bindValue(":tour_id", $object->getTourId());
        $stmt->bindValue(":start_date", $object->getStartDate());
        $stmt->bindValue(":end_date", $object->getEndDate());
        $stmt->bindValue(":watch", $object->getWatch());
        $stmt->bindValue(":created_at", $object->getCreatedAt());
        return $stmt->execute();
    }

}
