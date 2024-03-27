<?php

namespace TourTracker\Utilities\TourDataExtractor\Extractors;

use TourTracker\Utilities\TourDataExtractor\ExtractedDeparture;
use TourTracker\Utilities\TourDataExtractor\iExtractor;
use TourTracker\Utilities\URL;
use \Exception;

//Todo switch from interface to abstract method which defines first two functions.

class IntrepidExtractor implements iExtractor
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
        //throw new Exception("Unable to get tour name for INTREPID tour.");
        $content = $this->url->getContent();
        preg_match('/<title>([^<]+)<\/title>/', $content, $match);
        $titleInnerHtml = $match[1];
        // Tour Name is string left of '|' in title.
        $pieces = explode('|',$titleInnerHtml);
        return trim($pieces[0]);
    }

    public function getDepartureUpdates()
    {
        //throw new Exception("Unable to get departures for INTREPID tour.");
        $page = 1;
        $updates = array();
        do{
            $url = $this->getApiUrl($page);
            $data = $this->getApiData($url);
            $pages = $data->numberOfPages;
            $updates = array_merge($updates,$this->createUpdateObjectsFromData($data));
            $page++;
        }
        while($page <= $pages); //TODO Add condition for only loading pages within a reasonable time frame e.g. 1 year.
        return $updates;
    }

    //Find the 6 digit number in URL
    private function getProductId(){
        $url = $this->url->toString();
        preg_match("/\d{6}/",$url,$match);
        return $match[0];
    }

    private function getProductCode(){
        $content = $this->url->getContent();
        preg_match("/property=\"product:retailer_id\" content=\"([^\"]+)\"/",$content,$match);
        return $match[1];
    }

    private function getApiUrl($page = 1){
    $productId = $this->getProductId();
    $productCode = $this->getProductCode();
    $today = date("Y-m-d");
    $url =  "https://www.intrepidtravel.com/api/nuxt/product/available-years-months-and-departures?product_code=$productCode&product_id=$productId&start_date=$today&page_number=$page&currency_code=gbp";
    return $url;
    }

    private function getApiData($url){
        $url = new URL($url);
        $data = $url->getContent();
        return json_decode($data);
    }

    private function createUpdateObjectsFromData($data){
        $objects = array();
        foreach($data->departures as $property=>$value){
            if(preg_match("/^\d{4}-\d{2}-\d{2}$/",$property) == 1){
                $object = new ExtractedDeparture();
                $object->setStartDate($value->startDate);
                $object->setEndDate($value->endDate);
                $object->setAvailability($value->totalAvailability);
                $price = $value->lowestPrice->discountedPrice ?? $value->lowestPrice->price ?? 0;
                $object->setPrice($price);
                array_push($objects,$object);
            }
        }
        return $objects;
    }


}
