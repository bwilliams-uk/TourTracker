<?php
namespace TourTracker\Services\Secondary;
use TourTracker\Utilities\URL;
use TourTracker\Utilities\TourDataExtractor\TourDataExtractor;
use Exception;

class TourCreationService extends Service{

    protected function init(){
        $this->TourService = $this->ServiceLoader->get('TourService');
        $this->TourOperatorService = $this->ServiceLoader->get('TourOperatorService');
    }

    public function createFromUrl($urlText){
        if ($this->TourService->tourUrlExists($urlText)){
            throw new Exception("Tour Already Exists");
        }
        $url = new URL($urlText);
        $operator = $this->TourOperatorService->identifyOperatorByUrl($url);
        $name = $this->getTourNameFromUrl($url);
        $this->TourService->createTour($name,$urlText,$operator);
}

    private function getTourNameFromUrl(URL $url){
        $operator = $this->TourOperatorService->identifyOperatorByUrl($url,true);
        if(!$operator->getSupported()) throw new Exception("Operator is not supported.");
        $extractor = new TourDataExtractor($operator,$url);
        return $extractor->getTourName();
    }

}
