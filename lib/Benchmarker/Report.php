<?php
namespace Benchmarker;
class Report{
    const PAD_LENGTH = 16;

    private $outputFile; // Path to output to.
    private $outputData = ""; // String. The report content.

    public function setOutputFileName($path){
        $this->outputFile = $path;
    }

    public function createFromTimers($timers){
        $aggregated = $this->getLabelAggregationData($timers);

        $this->addHeader();
        $this->addLineBreaks(2);
        $this->addAggregationData($aggregated);
        $this->addLineBreaks(2);
        $this->addTimerData($timers);
        //$this->outputData .= var_dump($aggregated);
        file_put_contents($this->outputFile,$this->outputData);
    }
    private function createLine($time,$label){
        $time = str_pad($time,self::PAD_LENGTH);
        $this->outputData .= $time.$label."\n";
    }

    private function addLineBreaks($n = 1){
        $this->outputData .= str_repeat("\n",$n);
    }

    private function addHeader(){
        $this->outputData .= $_SERVER["SERVER_NAME"];
        $this->outputData .= $_SERVER["REQUEST_URI"];
        $this->outputData .= "\n\n";
        $this->outputData .= date("Y-m-d H:i:s");
        $this->outputData .= "\n\n";
    }

    private function addAggregationData($data){
        $this->outputData .= "AGGREGATED BY LABEL\n\n";
        $this->createLine("Duration","Label");
        foreach($data as $label=>$duration){
            $duration = $this->formatTime($duration);
            $this->createLine($duration,$label);
        }
    }

    private function addTimerData($timers){
        $this->outputData .= "TIMERS\n\n";
        $this->createLine("Duration","Label");
        foreach($timers as $timer){
            $time = $this->formatTime($timer->getDuration());
            $this->createLine($time,$timer->label);
        }
    }

    private function getLabelAggregationData($timers){
        $data = array();
        foreach($timers as $timer){
            $label = $timer->label;
            if(!isset($data[$label])){
                $data[$label] = $timer->getDuration(true);
            }
            else{
                $data[$label] += $timer->getDuration(true);
            }
        }
        return $data;
    }

    private function formatTime($time){
        return ($time < 0.001) ? "< 0.001" : $time;
    }

}
