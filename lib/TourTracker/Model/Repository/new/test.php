<?php
include("Repository.php");
include("TourRepository.php");
include("../../DomainObject/Tour.php");
use TourTracker\Model\DomainObject\Tour;

$pdo = new PDO("mysql:dbname=tour_tracker;host=localhost;","root","");
$rep = new TourTracker\Model\Repository\new\TourRepository($pdo);


$method = 'deleteByObj';

switch($method){

    case 'get':
        print_r($obj = $rep->get(6));
        break;
    case 'update':
        $obj = $rep->get(6);
        $obj->setOperatorId(1);
        $rep->save($obj);
        break;
    case 'create':
        $obj = new Tour();
        $obj->setName("test");
        $obj->setUrl("url");
        $obj->setOperatorId(3);
        $obj->setCreatedAt(date("Y-m-d H:i:s"));
        $rep->save($obj);
        break;
    case 'deletebyId':
        $id = 65;
        $rep->remove($id);
        break;
    case 'deleteByObj':
        $id = 66;
        $obj = $rep->get($id);
        $rep->remove($obj);
        break;

}

