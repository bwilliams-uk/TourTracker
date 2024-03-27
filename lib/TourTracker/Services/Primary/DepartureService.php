<?php
/*
Purpose:
To provide an interface for DepartureRepository and DepartureIndex Classes.
*/


namespace TourTracker\Services\Primary;
use TourTracker\Model\DomainObject\Tour;
use TourTracker\Model\DomainObject\Departure;
use Exception;
use PDO;
use Benchmarker\Benchmarker;

class DepartureService extends BaseService{

    public function createDepartureIfNotExists(Tour $tour, $startDate, $endDate){
        if(!$this->departureExists($tour,$startDate,$endDate)){
            $this->createDeparture($tour,$startDate,$endDate);
        }
    }

    public function deleteByTourId($tourId){
        /* Refactor to:
        $index = $this->index;
        $filter = $index->createFilter();
        $filter['tourId'] = $tourId;
        $matches = $index->find($filter);
        */
        $matches = $this->index->tourIdEquals($tourId);
        $this->repository->remove($matches);
    }

    public function watch($departureId){
        $this->setWatch($departureId, 1);
    }

    public function unwatch($departureId){
        $this->setWatch($departureId, 0);
    }


    public function getDeparture(Tour $tour, $startDate, $endDate){
        $matches = $this->index->byNaturalId($tour->getId(),$startDate,$endDate);

        /*Refactor to:
        $index = $this->index;
        $filter = $index->createFilter();
        $filter['tourId'] = $tour->getId();
        $filter['startDate'] = $startDate;
        $filter['endDate'] = $endDate;
        $matches = $index->find($filter);
        */

        $count = count($matches);
        if ($count !== 1){
            throw new Exception("Expected to find 1 departure matching criteria: $count found.");
        }
        return $this->repository->get($matches[0]);
    }

    public function getAvailableDepartures($tourId){
        /*Refactor to:
        $index = $this->index;
        $filter = $index->createFilter();
        $filter['tourId'] = $tourId;
        $filter['available'] = 1;
        $matches = $index->find($filter);
        */
        $matches = $this->index->availableByTourId($tourId);
        return $this->repository->get($matches);
    }

    private function createDeparture(Tour $tour, $startDate, $endDate){
        $departure = new Departure();
        $departure->setTourId($tour->getId());
        $departure->setStartDate($startDate);
        $departure->setEndDate($endDate);
        $this->repository->save($departure);
    }

    private function departureExists(Tour $tour, $startDate, $endDate){
        /*Refactor to:
        $index = $this->index;
        $filter = $index->createFilter();
        $filter['tourId'] = $tour->getId();
        $filter['startDate'] = $startDate;
        $filter['endDate'] = $endDate;
        $matches = $index->find($filter);
        */
        $matches = $this->index->byNaturalId($tour->getId(),$startDate,$endDate);
        return (count($matches) === 1);

    }

    private function setWatch($departureId,$val){
        $departure = $this->repository->get($departureId);
        if(!$departure) throw new Exception("Could not find departure with ID: $departureId");
        $departure->setWatch($val);
        $this->repository->save($departure);
    }
}
