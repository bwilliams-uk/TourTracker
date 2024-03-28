<?php
namespace Benchmarker;
use \Exception;
class Timer{
    public $label;
    public $start;
    public $end;
    private $closed = false;

    public function __construct($label){
        $this->label = $label;
        $this->start = microtime(true);
        $this->end = null;
    }
    public function close()
    {
        if($this->closed){
            throw new Exception("Timer has already been closed.");
        }
        $this->end = microtime(true);
        $this->closed = true;
    }
    public function getDuration()
    {
        if(!$this->closed){
            throw new Exception("Timer '$this->label' has not been closed.");
        }
        $round = round($this->end - $this->start,3);
		return $round;
    }

    public function __destruct(){
        Benchmarker::onTerminate();
    }
}
