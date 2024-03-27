<?php
namespace TourTracker\Services\Secondary;
use TourTracker\Model\DomainObject\Tour;
use TourTracker\Utilities\URL;
use TourTracker\Utilities\TourDataExtractor\TourDataExtractor;
use TourTracker\Utilities\TourDataExtractor\ExtractedDeparture;

class TourSyncService extends Service{

    protected function init(){
        $this->TourService = $this->ServiceLoader->get('TourService');
        $this->TourOperatorService = $this->ServiceLoader->get('TourOperatorService');
        $this->DepartureService = $this->ServiceLoader->get('DepartureService');
        $this->DepartureUpdateService = $this->ServiceLoader->get('DepartureUpdateService');
    }

    public function sync($tourId){
        $tour = $this->TourService->getById($tourId);
        $url = new URL($tour->getUrl());
        $operator = $this->TourOperatorService->identifyOperatorByUrl($url, true); // Note: Operator is already known in $tour->getOperatorId().
        $extractor = new TourDataExtractor($operator,$url);
        $updates = $extractor->getDepartureUpdates();
        $this->importUpdates($updates,$tour);
    }

    // Sync $n tours randomly from all that are due.
    public function syncRandomTour($n = 1){
        $dueTours = $this->TourService->getTourIdsDueSync();
        $n = min($n,count($dueTours));
        shuffle($dueTours);
        for($i = 0 ; $i < $n; $i++){
            $this->sync($dueTours[$i]);
        }
    }

    private function importUpdates(array $updates, Tour $tour){
        foreach($updates as $u){
            $this->importUpdate($u,$tour);
        }
    }

    private function importUpdate(ExtractedDeparture $update, Tour $tour){
        $startDate = $update->getStartDate();
        $endDate = $update->getEndDate();
        $service = $this->DepartureService;
        $service->createDepartureIfNotExists($tour,$startDate,$endDate);
        $departure = $service->getDeparture($tour,$startDate,$endDate);
        $service = $this->DepartureUpdateService;
        $service->createDepartureUpdate($departure,$update->getPrice(),$update->getAvailability());
    }
}
