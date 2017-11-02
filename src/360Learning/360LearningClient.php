<?php

namespace 360Learning;

use 360Learning\Models\DynamicResponseModel as DynamicResponseModel;
use 360Learning\360LearningClientBase as 360LearningClientBase;


class 360LearningClient extends 360LearningClientBase {
    
    private $server_token = NULL;
    
    /**
     * Create a new 360LearningClient.
     *
     * @param string $company : Your company id (company)
     * @param string $apiKey : Your API key (apiKey)
     * @param integer $timeout The timeout, in seconds to wait for an API call to complete before throwing an Exception.
     */
    function __construct($serverToken, $timeout = 30) {
        parent::__construct($serverToken, 'X-Postmark-Server-Token', $timeout);
    }
    
       
    
    
    
    
}

?>
