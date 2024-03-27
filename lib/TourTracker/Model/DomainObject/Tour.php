<?php
namespace TourTracker\Model\DomainObject;
class Tour{

    private $id;
    private $name;
    private $operatorId;
    private $url;
    private $createdAt;

    public function __construct(){
        $now = date("Y-m-d H:i:s");
        $this->setCreatedAt($now);
    }

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getOperatorId(){
        return $this->operatorId;
    }

    public function setOperatorId($operatorId){
        $this->operatorId = $operatorId;
    }

    public function getUrl(){
        return $this->url;
    }

    public function setUrl($url){
        $this->url = $url;
    }

    public function getCreatedAt(){
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt){
        $this->createdAt = $createdAt;
    }


}
