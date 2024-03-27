<?php
/*
Purpose: To create a copy of extracted departure
data as a compressed JSON file.

Backup folder is specified in TourTracker\Config file.

*/

namespace TourTracker\Utilities\TourDataExtractor;
use TourTracker\Config;
use StdClass;
use ZipArchive;

class BackupFileBuilder{
    private $data;
    private $fileName = "";
    public function __construct($tourName,$tourUrl,$updates){
        $data = new StdClass();
        $data->tour_name = $tourName;
        $data->tour_url = $tourUrl;
        $data->timestamp = time();
        $data->departures = $this->createDeparturesObject($updates);
        $this->data = $data;

        //set zipArchive based on URL hash.
        $this->zipArchiveName = substr(md5($tourUrl),0,16).'.zip';
        //set Filename based on timestamp.
        $this->fileName = time().'.json';
    }

    private function createDeparturesObject($updates){
        $departures = [];
        foreach($updates as $u){
            $departure = new StdClass();
            $departure->start_date = $u->getStartDate();
            $departure->end_date = $u->getEndDate();
            $departure->availability = $u->getAvailability();
            $departure->price = $u->getPrice();
            array_push($departures,$departure);
        }
        return $departures;
    }

    public function save(){
        // Create directory if not exists
        $saveDir = Config::EXTRACTOR_JSON_BACKUP_DIR;
        if(!is_dir($saveDir)){
            mkdir($saveDir,0777,true);
        }
        //encode content as JSON
        $json = json_encode($this->data,JSON_PRETTY_PRINT);

        //Write/Append to file
        $zpath = $saveDir.'/'.$this->zipArchiveName;
        $zip = new ZipArchive();
        $zip->open($zpath, ZipArchive::CREATE);
        $zip->addFromString($this->fileName,$json);
        $zip->close();

    }

}
