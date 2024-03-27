<?php
namespace TourTracker\Model\Repository;

class DepartureUpdateRepository extends Repository{

    protected $tableName = "departure_update";

    //First column must be AUTOINCREMENT ID
    protected $columnNames = array('id','departure_id','price','availability','created_at');

}
