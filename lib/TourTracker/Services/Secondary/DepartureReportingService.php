<?php
/*
Purpose to create a JSON report of information pertaining
to the given departure.

NOT IN ACTIVE USE. Has poor performance compared with the
Reports (Reports/DepartureReport) Method which outputs a
query directly from DB.

*/


namespace TourTracker\Services\Secondary;
use TourTracker\Model\DomainObject\Departure;
use TourTracker\Utilities\TourDataExtractor\TourDataExtractor;
use Exception;
use StdClass;

class DepartureReportingService extends BaseService{

    protected function init(){
        $this->DepartureService = $this->ServiceLoader->get('DepartureService');
        $this->DepartureUpdateService = $this->ServiceLoader->get('DepartureUpdateService');
        $this->TourService = $this->ServiceLoader->get('TourService');
        $this->TourOperatorService = $this->ServiceLoader->get('TourOperatorService');
    }

    public function createReport($departure){
        if(!$departure instanceof Departure){
            $departure = $this->DepartureService->getById($departure);
        }
        $tour = $this->TourService->getById($departure->getTourId());
        $operator = $this->TourOperatorService->getById($tour->getOperatorId());
        $latestUpdate = $this->DepartureUpdateService->getLatestByDepartureId($departure->getId());


        $report = new StdClass();
        $report->departure_id = $departure->getId();
        $report->start_date = $departure->getStartDate();
        $report->end_date = $departure->getEndDate();
        $report->watching = $departure->getWatch();
        $report->tour_id = $tour->getId();
        $report->tour_name = $tour->getName();
        $report->tour_url = $tour->getUrl();
        $report->operator_id = $operator->getId();
        $report->operator_name = $operator->getName();
        $report->sync_date = $latestUpdate->getCreatedAt();
        $report->sync_days_ago = $latestUpdate->age();
        return $report;
    }

}
