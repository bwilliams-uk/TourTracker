<?php
namespace Benchmarker;
class Benchmarker{
    private static $outputFile = __DIR__.'/report.txt';
    private static $timers = array();
    private static $terminated = false;

    public static function createTimer($label){
        $timer = new Timer($label);
        array_push(static::$timers,$timer);
        return $timer;
    }

    private static function createReport(){
        $file = static::$outputFile;
        $report = new Report();
        $report->setOutputFilename($file);
        $report->createFromTimers(static::$timers);
    }

    public static function onTerminate(){
        if(static::$terminated === true){
            return true;
        }
        static::$terminated = true;
        static::createReport();
    }
}
