<?php
namespace TourTracker\Utilities\TourDataExtractor;
use TourTracker\Utilities\URL;
interface iExtractor{
public function __construct(URL $url);
public function getTourName();
public function getDepartureUpdates();
}
