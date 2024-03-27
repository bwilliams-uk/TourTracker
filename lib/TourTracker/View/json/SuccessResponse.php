<?php
namespace TourTracker\View\json;
use StdClass;

class SuccessResponse{
    private $data;

    public function __construct(){
        $this->data = new StdClass();
    }
    public function setSuccess($val){
        $this->data->success = $val;
    }
    public function setMessage($msg){
        $this->data->message = $msg;
    }
    public function send(){
        header("content-type:application/json");
        $encoded = json_encode($this->data,JSON_PRETTY_PRINT);
        echo $encoded;
    }
}
