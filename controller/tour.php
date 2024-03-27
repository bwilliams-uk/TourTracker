<?php

/*
Purpose to provide an HTTP API for Modification
Actions pertaining to 'Tours'. I.e.:
 - Creating
 - Syncing
 - Deleting

*/

namespace controller;

use TourTracker\TourTracker;
use TourTracker\Services\ServiceLoader;
use TourTracker\View\Json\SuccessResponse;
use Exception;

class tour
{
    private $pdo;

    public function __construct()
    {
        $app = new TourTracker();
        $pdo =  $app->createPdo();
        $this->ServiceLoader = new ServiceLoader($pdo);
    }


    public function create()
    {
        $pdo = $this->pdo;
        $response = new SuccessResponse();
        try {
            $service = $this->ServiceLoader->get("TourCreationService");
            $url = $_REQUEST["url"] ?? "";
            $service->createFromUrl($url);
            $response->setSuccess(1);
            $response->setMessage("");
        } catch (Exception $e) {
            $response->setSuccess(0);
            $response->setMessage($e->getMessage());
        } finally {
            $response->send();
        }
    }

    public function sync($tourId = null)
    {
        $pdo = $this->pdo;
        $response = new SuccessResponse();
        try {
            $service = $this->ServiceLoader->get("TourSyncService");
            $service->sync(intval($tourId));
            $response->setSuccess(1);
            $response->setMessage("");
        } catch (Exception $e) {
            $response->setSuccess(0);
            $response->setMessage($e->getMessage());
        } finally {
            $response->send();
        }
    }

    public function delete($tourId = null)
    {
        $pdo = $this->pdo;
        $response = new SuccessResponse();
        try {
            $service = $this->ServiceLoader->get("TourDeletionService");
            $service->delete(intval($tourId));
            $response->setSuccess(1);
            $response->setMessage("");
        } catch (Exception $e) {
            $response->setSuccess(0);
            $response->setMessage($e->getMessage());
        } finally {
            $response->send();
        }
    }
}
