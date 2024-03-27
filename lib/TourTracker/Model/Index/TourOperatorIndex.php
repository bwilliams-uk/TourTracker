<?php
/*
Purpose:
To produce an Array of TourOperator IDs which meet the requested criteria.
*/

namespace TourTracker\Model\Index;
use PDO;
class TourOperatorIndex extends Index{

    protected $filterVariables = array(
        'web'
    );
}
