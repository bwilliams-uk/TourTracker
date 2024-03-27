<?php
/* Todo:
Convert to a general URL class that can retrieve content
and Identify the domain name.
*/



namespace TourTracker\Utilities;
class URL{
    private $url;
    private $content = null;

    public function __construct($url){
        $this->url = $url;
    }
    public function toString()
    {
        return $this->url;
    }

    public function getHost(){
        $a = parse_url($this->url);
        return $a["host"] ?? null;
    }


    public function getContent()
    {
        if($this->content === null){
            $this->loadContent();
        }
        return $this->content;
    }




    private function loadContent()
    {
        $url = $this->url;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        $content = curl_exec($ch);
        curl_close($ch);
        $this->content = $content;
    }
}

/* OLD METHODS - TODO Put in appropriate classes

        switch ($this->identifyOperator()){
            case "G Adventures":
                $tourExtractor = new operators\GAdventures\TourPage($content);
                break;
            default:
                throw new Exception("Unable to create tour page object for this operator.");
        }
        return $tourExtractor;
    }



    public function identifyOperator($column = "name"){
        $report = new OperatorReport();
        $operators = $report->toEntities();
        $url = $this->toString();

        $returnValue = false;
        foreach($operators as $operator){
            if(stripos($url,$operator->getWeb()) !== false){
                if($column == "name") $returnValue = $operator->getName();
                if($column == "id") $returnValue = $operator->getId();
            }
        }
        return $returnValue;
        }
}
*/
