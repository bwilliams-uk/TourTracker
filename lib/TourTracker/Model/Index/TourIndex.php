<?php
/*
Purpose:
To produce an Array of Tour IDs which meet the requested criteria.
*/

namespace TourTracker\Model\Index;
use TourTracker\Config;

class TourIndex extends Index{

    protected $defaultBindings = array(
        'syncLimit' => Config::SYNC_LIMIT
        );

    protected $filterVariables = array(
        'url',
        'syncDue'
    );

}
