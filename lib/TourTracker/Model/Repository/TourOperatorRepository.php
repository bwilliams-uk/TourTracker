<?php
namespace TourTracker\Model\Repository;

class TourOperatorRepository extends Repository{

    protected $tableName = "tour_operator";

    //First column must be AUTOINCREMENT ID
    protected $columnNames = array('id','name','web','supported');

}
