<?php
namespace TourTracker\Services\Secondary;

class TourDeletionService extends BaseService{

    protected function init(){
        $this->DepartureUpdateService = $this->ServiceLoader->get('DepartureUpdateService');
        $this->DepartureService = $this->ServiceLoader->get('DepartureService');
        $this->TourService = $this->ServiceLoader->get('TourService');
    }

    public function delete($tourId){
        $this->DepartureUpdateService->deleteByTourId($tourId);
        $this->DepartureService->deleteByTourId($tourId);
        $this->TourService->deleteById($tourId);
    }
}
