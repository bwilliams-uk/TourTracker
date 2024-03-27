<?php
namespace Benchmarker;
class Benchmarker{
    private static $timers = array();
    private static $reportDirectory = __DIR__.'/reports/';

    public static function createTimer($label){
        $timer = new Timer();
        $timer->label = $label;
        $timer->start = microtime(true);
        $timer->end = null;
        array_push(static::$timers,$timer);
        return $timer;
    }

    public static function createReport(){
        $data = str_pad("Duration",16)."Label\n";
        foreach(static::$timers as $timer){
            $d = str_pad($timer->getDuration(),16);
            $l = $timer->label;
            $data .= "$d$l\n";
        }
        //$fileName = time().'.txt';
        //$path = static::$reportDirectory.$fileName;
        $path = __DIR__."/report.txt";
        file_put_contents($path,$data);

    }


}
