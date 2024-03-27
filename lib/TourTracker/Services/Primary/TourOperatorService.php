<?php
/*
Purpose:
To provide an interface for TourOperatorRepository and TourOperatorIndex Classes.
*/

namespace TourTracker\Services\Primary;
use TourTracker\Model\Index\TourOperatorIndex;
use TourTracker\Model\Repository\TourOperatorRepository;
use TourTracker\Utilities\URL;
use Exception;

class TourOperatorService extends BaseService{

    //Return the Operator Id/Object which the URL matches
    public function identifyOperatorByUrl(URL $url,$returnObject = false){

        $domain = $url->getHost();
        $matches = $this->index->webSimilarTo($domain);
        $count = count($matches);
        if($count === 0){
             throw new Exception("Unknown Operator.");
        }
        else if($count > 1){
            throw new Exception("Operator is ambiguous.");
        }
        if($returnObject){
            return $this->repository->get($matches[0]);
        }
        else{
            return $matches[0];
        }
    }
}
