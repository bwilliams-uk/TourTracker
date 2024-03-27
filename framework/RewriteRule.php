<?php

class RewriteRule{
    private $inputRegex;
    private $outputFormat;

    public function __construct($inputPattern,$outputPattern){
        $this->createInputRegex($inputPattern);
        $this->formatOutputTemplate($inputPattern,$outputPattern);
    }

    public function rewrite($input){
        $input = strtolower($input);
        $result = preg_match_all($this->inputRegex,$input,$matches);

        //Return the input string if not a match
        if($result !== 1){
            return $input;
        }
        //Remove the initial match that is the whole string
        $matches = array_slice($matches,1);

        //Count the number of 'wildcard' matches
        $count = count($matches);

        $out = $this->outputFormat;
        //Replace '%i' placeholders with each wildcard match.
        for($i=0;$i<$count;$i++){
            $out = str_replace("%$i",$matches[$i][0],$out);
        }
        return $out;
    }


    private function createInputRegex($inputPattern){
        //Escape forward slashes
        $inputPattern = str_replace('/',"\/",$inputPattern);

        //Replace {placeholder} with "(\w)+"
        $rgx = preg_replace("/\{(\w+)\}/",'(\w+)',$inputPattern);

        //Replace {#placeholder} with "(\d)+"
        $rgx = preg_replace("/\{#(\w+)\}/",'(\d+)',$rgx);

        //Apply start/end string indicators
        $this->inputRegex = '/^'.$rgx.'$/';
    }

    private function formatOutputTemplate($in,$out){

        //Find out what {placeholders} are in the input
        preg_match_all("/\{#?(\w+)\}/",$in,$matches);

        $count = count($matches[0]);
        for($i = 0; $i < $count; $i++){
            //Replace each {placeholder} with %i in the output string to indicate replace order.
            $out = str_replace($matches[0][$i],"%$i",$out);
        }

        $this->outputFormat = $out;
    }
}

