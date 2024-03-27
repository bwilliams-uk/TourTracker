<?php

namespace TourTracker\Utilities\TourDataExtractor\Extractors;

use TourTracker\Utilities\TourDataExtractor\ExtractedDeparture;
use TourTracker\Utilities\TourDataExtractor\iExtractor;
use TourTracker\Utilities\URL;
use \Exception;

//Todo switch from interface to abstract method which defines first two functions.

class GadventuresExtractor implements iExtractor
{
    private $url;
    public function __construct(URL $url)
    {
        $this->url = $url;
    }

    public function getUrl(){
        return $this->url;
    }

    public function getTourName()
    {
        //throw new Exception("Unable to get tour name for GADVENTURES tour.");
        $content = $this->url->getContent();
        preg_match('/<meta property="og:title" content="([^\"]+)"\/>/', $content, $match);
        $tourName = (isset($match[1])) ? htmlspecialchars_decode($match[1]) : false;
        return $tourName;
    }

    public function getDepartureUpdates()
    {
        //throw new Exception("Unable to get departures for GADVENTURES tour.");
        $apiUrl = new URL($this->getApiUrl());
        $departureData = json_decode($apiUrl->getContent());
        $departureUpdates = $this->convertDataToDepartureUpdates($departureData);
        return $departureUpdates;
    }

    private function getApiUrl()
    {
        $content = $this->url->getContent();
        //Use Regex to match the API ID
        preg_match('/<meta property="bt:id" content="(\d+)"\/>/',$content,$match);
        $id = $match[1];
        $url = "https://www.gadventures.com/trips/json/departures/?trip_id=$id&show_waitlist=true";
        return $url;
    }

    private function convertDataToDepartureUpdates($data){
        $departures = [];
        foreach($data as $d){
            $departure = new ExtractedDeparture();
            $departure->setStartDate($d->start_date);
            $departure->setEndDate($d->end_date);
            $departure->setAvailability($d->availability);
            $price = $this->selectLowestPrice($d->price_set);
            $departure->setPrice($price);
            $departures[] = $departure;
        }
        return $departures;
    }

    private function selectLowestPrice($priceSet){
        $count = count($priceSet);
        for($i = 0; $i<$count; $i++){
            if(($i === 0) or ($priceSet[$i]->price < $lowestPrice)){
                $lowestPrice = $priceSet[$i]->price;
            }
        }
        return $lowestPrice;
    }
}
