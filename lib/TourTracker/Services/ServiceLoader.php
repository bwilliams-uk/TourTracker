<?php
/*
Purpose: To Construct Services with the required dependencies.
*/

namespace TourTracker\Services;

use Exception;

use TourTracker\Model\Repository\TourOperatorRepository;
use TourTracker\Model\Repository\TourRepository;
use TourTracker\Model\Repository\DepartureRepository;
use TourTracker\Model\Repository\DepartureUpdateRepository;

use TourTracker\Model\Index\TourOperatorIndex;
use TourTracker\Model\Index\TourIndex;
use TourTracker\Model\Index\DepartureIndex;
use TourTracker\Model\Index\DepartureUpdateIndex;

/* Primary Services must be given an associated Repository
and Index. Their single responsibility is to provide an
interface to these two classes.

Construct (Repository, Index)
*/

use TourTracker\Services\Primary\TourOperatorService;
use TourTracker\Services\Primary\TourService;
use TourTracker\Services\Primary\DepartureService;
use TourTracker\Services\Primary\DepartureUpdateService;

/*Secondary Services have no direct access to repositories
or indexes but may combine the functionality other services.

Construct (ServiceLoader)
*/

use TourTracker\Services\Secondary\TourCreationService;
use TourTracker\Services\Secondary\TourDeletionService;
use TourTracker\Services\Secondary\TourSyncService;
use TourTracker\Services\Secondary\TourAdjoinmentService;



/*Tertiary Services, should be avoided where possible,
the service is constructed with PDO instance. Use only
where repository pattern is not appropriate. */



class ServiceLoader{

    private $repositories;
    private $indexes;

    public function __construct($pdo){
        $this->loadRepositories($pdo);
        $this->loadIndexes($pdo);
    }

    private function loadRepositories($pdo){
        $r = array();
        $r['TourOperator'] = new TourOperatorRepository($pdo);
        $r['Tour'] = new TourRepository($pdo);
        $r['Departure'] = new DepartureRepository($pdo);
        $r['DepartureUpdate'] = new DepartureUpdateRepository($pdo);
        $this->repositories = $r;
    }
    private function loadIndexes($pdo){
        $i = array();
        $i['TourOperator'] = new TourOperatorIndex($pdo);
        $i['Tour'] = new TourIndex($pdo);
        $i['Departure'] = new DepartureIndex($pdo);
        $i['DepartureUpdate'] = new DepartureUpdateIndex($pdo);
        $this->indexes = $i;
    }


    public function get($serviceName){
        $n = str_replace('Service','',$serviceName);
        switch($serviceName){
            case 'TourOperatorService':
                $rep = $this->repositories[$n];
                $ind = $this->indexes[$n];
                return new TourOperatorService($rep,$ind);
                break;
            case 'TourService':
                $rep = $this->repositories[$n];
                $ind = $this->indexes[$n];
                return new TourService($rep,$ind);
                break;
            case 'DepartureService':
                $rep = $this->repositories[$n];
                $ind = $this->indexes[$n];
                return new DepartureService($rep,$ind);
                break;
            case 'DepartureUpdateService':
                $rep = $this->repositories[$n];
                $ind = $this->indexes[$n];
                return new DepartureUpdateService($rep,$ind);
                break;
            case 'TourCreationService':
                return new TourCreationService($this);
                break;
            case 'TourDeletionService':
                return new TourDeletionService($this);
                break;
            case 'TourSyncService':
                return new TourSyncService($this);
                break;
            case 'TourAdjoinmentService':
                return new TourAdjoinmentService($this);
                break;
            default:
                throw new Exception("Service Loader unable to load '$serviceName'");
                break;
        }
    }

    }
