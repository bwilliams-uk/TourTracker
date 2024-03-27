<?php
/*
Purpose:
To provide an interface for DepartureUpdateRepository and DepartureUpdateIndex Classes.
*/

namespace TourTracker\Services\Primary;
use TourTracker\Model\DomainObject\Departure;
use TourTracker\Model\DomainObject\DepartureUpdate;
use Benchmarker\Benchmarker;
use Exception;
use PDO;

class DepartureUpdateService extends Service{

    private $constructedAt;

    protected function init(){
        $this->constructedAt = date("Y-m-d H:i:s"); //Ensure INSERTS have same creation time
    }

    public function deleteByTourId($tourId){
        $index = $this->index;
        $filter = $index->createFilter();
        $filter['tourId'] = $tourId;
        $matches = $index->find($filter);
        $this->repository->remove($matches);
    }

    public function createDepartureUpdate(Departure $departure, $price, $availability){
        $update = new DepartureUpdate();
        $update->setDepartureId($departure->getId());
        $update->setPrice($price);
        $update->setAvailability($availability);
        $update->setCreatedAt($this->constructedAt); // Ensure identical creation time
        $this->repository->save($update);
    }

    public function getLatestUpdates(){
        $index = $this->index;
        $filter = $index->createFilter();
        $filter['latest'] = 1;
        $matches = $index->find($filter);
        return $this->repository->get($matches);
    }

    public function getLatestByDepartureId($id){
        $index = $this->index;
        $filter = $index->createFilter();
        $filter['latest'] = 1;
        $filter['departureId'] = $id;
        $matches = $index->find($filter);
        $obj = $this->repository->get($matches[0]); // Index currently sorts created DESC
        return $obj;
    }
}
