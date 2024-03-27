<?php

/*
Purpose to provide an HTTP API for actions regarding departures.
*/


namespace controller;

use TourTracker\TourTracker;
use TourTracker\Services\ServiceLoader;
use TourTracker\View\Json\SuccessResponse;
use Exception;

class departure
{
    private $pdo;

    public function __construct()
    {
        $app = new TourTracker();
        $pdo =  $app->createPdo();
        $sl = new ServiceLoader($pdo);
        $this->DepartureService = $sl->get('DepartureService');
    }

    public function watch($departureId)
    {
        $this->setWatch($departureId, 1);
    }
    public function unwatch($departureId)
    {
        $this->setWatch($departureId, 0);
    }

    private function setWatch($departureId, $value)
    {
        $response = new SuccessResponse();
        try {
            $service = $this->DepartureService;
            $url = $_REQUEST["url"] ?? "";
            if ($value === 1) $service->watch($departureId);
            if ($value === 0) $service->unwatch($departureId);
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
