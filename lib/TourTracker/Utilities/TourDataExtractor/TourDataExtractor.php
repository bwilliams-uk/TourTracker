<?php
/* Purpose:
To act as a proxy to the underlying extractor class
which is operator specific.

*/

namespace TourTracker\Utilities\TourDataExtractor;

use TourTracker\Config;
use TourTracker\Model\DomainObject\TourOperator;
use TourTracker\Utilities\URL;
use Exception;

class TourDataExtractor{

    //Defined here so that updates to database name fields do not affect ability to find extractor classes.
    const OPERATOR_CLASS_NAMES = array (
        1 => "Gadventures",
        2 => "Intrepid",
        3 => "Trutravels",
        4 => "Introtravel"
        );
    const EXTRACTOR_NAMESPACE = "TourTracker\\Utilities\\TourDataExtractor\Extractors\\";
    private $extractor;

    //The constructor must load the operator specific extractor into the $extractor property.
    public function __construct(TourOperator $operator, URL $url){

        //Throw exception if operator is not supported:
        if(!$operator->getSupported()){
           // throw new Exception("Operator is not supported.");
        }

        $id = $operator->getId();
        //Resolve the name of the extractor class for the given operator:
        $extractorClassName = self::EXTRACTOR_NAMESPACE.self::OPERATOR_CLASS_NAMES[$id]."Extractor";
        $this->extractor = new $extractorClassName($url);

        //Check the extractor class implements the required interface:
        if(!$this->extractor instanceof iExtractor){
            throw new Exception("Extractor must implement iExtractor interface.");
        }
    }

    public function getTourName(){
        return $this->extractor->getTourName();
    }
    public function getUrl(){
        return $this->extractor->getUrl();
    }

    public function getDepartureUpdates($bypassBackup = false){
        $updates = $this->extractor->getDepartureUpdates();
        if(!$bypassBackup){
            $this->backupUpdates($updates);
        }
        return $updates;
    }

    //Backs up the updates to a text file if flag is set in Config file.
    private function backupUpdates($updates){
        if(!Config::EXTRACTOR_JSON_BACKUP){
            return false; //backup disabled
        }
        $tourName = $this->getTourName();
        $url = $this->getUrl()->toString();
        $file = new BackupFileBuilder($tourName,$url,$updates);
        $file->save();
    }
}
