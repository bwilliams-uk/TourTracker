<?php
namespace TourTracker\Model\Repository;

class TourRepository extends Repository{

    protected $tableName = "tour";

    //First column must be AUTOINCREMENT ID
    protected $columnNames = array('id','name','operator_id','url','created_at');

}
