<?php
namespace TourTracker\Model\Repository;

class DepartureRepository extends Repository{

    protected $tableName = "departure";

    //First column must be AUTOINCREMENT ID
    protected $columnNames = array('id','tour_id','start_date','end_date','watch','created_at');

}
