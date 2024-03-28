<?php
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


