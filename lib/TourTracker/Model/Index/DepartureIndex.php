<?php
/*
Purpose:
To produce an Array of Departure IDs which meet the requested criteria.
*/

namespace TourTracker\Model\Index;
class DepartureIndex extends Index{

    protected $filterVariables = array(
        'tourId',
        'startDate',
        'endDate',
        'available'
    );
}
