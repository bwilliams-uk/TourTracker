<?php
/*
Purpose:
To produce an Array of DepartureUpdate IDs which meet the requested criteria.
*/

namespace TourTracker\Model\Index;
class DepartureUpdateIndex extends Index{

    protected $filterVariables = array(
        'departureId',
        'tourId',
        'latest'
    );
}
