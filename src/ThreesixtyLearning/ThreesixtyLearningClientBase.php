<?php

/*
 * Author:   3X Consultants - Jean-Baptiste ROUANET (jbrouanet@oxalia-software.com)
 */

namespace Kassoulet\ThreesixtyLearningBundle\ThreesixtyLearning;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use ThreesixtyLearning\Models\ThreesixtyLearningException;

/**
 * This is the core class that interacts with the 360Learning API. All clients should
 * inherit fromt this class.
 */
abstract class ThreesixtyLearningClientBase {
    
    /**
     * BASE_URL is "http(s)://app.360learning.com/api/v1"
     *
     * You may modify this value to disable SSL support, but it is not recommended.
     *
     * @var string
     */
    public static $BASE_URL = "https://app.360learning.com/api/v1";
    
    /**
     * VERIFY_SSL is defaulted to "true".
     *
     * In some PHP configurations, SSL/TLS certificates cannot be verified.
     * Rather than disabling SSL/TLS entirely in these circumstances, you may
     * disable certificate verification. This is dramatically better than disabling
     * connecting to the Postmark API without TLS, as it's still encrypted,
     * but the risk is that if your connection has been compromised, your application could
     * be subject to a Man-in-the-middle attack. However, this is still a better outcome
     * than using no encryption at all.
     */
    
    public static $VERIFY_SSL= true;
    
    protected $authorization_company = NULL;
    protected $authorization_token = NULL;
    
    protected $version = NULL;
    protected $os = NULL;
    protected $timeout = 30;
    
    /** @var  Client */
    protected $client;
    
    protected function __construct($token, $company, $timeout = 30) {        
        $this->authorization_token = $token;
        $this->authorization_company = $company;
        
        $this->version = phpversion();
        $this->os = PHP_OS;
        $this->timeout = $timeout;
    }
    
    
    /**
     * Return the injected GuzzleHttp\Client or create a default instance
     * @return Client
     */
    protected function getClient() {
        if(!$this->client) {
            $this->client = new Client([
                'base_uri' => self::$BASE_URL,
                RequestOptions::VERIFY  => self::$VERIFY_SSL,
                RequestOptions::TIMEOUT => $this->timeout,
            ]);
        }
        return $this->client;
    }
    
    /**
     * Provide a custom GuzzleHttp\Client to be used for HTTP requests
     *
     * @see http://docs.guzzlephp.org/en/latest/ for a full list of configuration options
     *
     * The following options will be ignored:
     * - http_errors
     * - headers
     * - query
     * - json
     *
     * @param Client $client
     */
    public function setClient(Client $client) {
        $this->client = $client;
    }
    
    /**
     * The base request method for all API access.
     *
     * @param string $method The request VERB to use (GET, POST, PUT, DELETE)
     * @param string $path The API path.
     * @param array $body The content to be used (either as the query, or the json post/put body)
     * @return object
     *
     * @throws PostmarkException
     */
    protected function processRestRequest($method = NULL, $path = NULL, array $body = []) {
        $client = $this->getClient();
        
        $options = [
            RequestOptions::HTTP_ERRORS => false,
            RequestOptions::HEADERS => [
                'User-Agent' => "3xConsultants-360LBundle-PHP (PHP Version:{$this->version}, OS:{$this->os})",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
                ],
                ];
        
        if(!empty($body)) {
            
            $cleanParams = array_filter($body, function($value) {
                return $value !== null;
            });
                
                switch ($method) {
                    case 'GET':
                    case 'HEAD':
                    case 'DELETE':
                    case 'OPTIONS':
                        $options[RequestOptions::QUERY] = $cleanParams;
                        break;
                    case 'PUT':
                    case 'POST':
                    case 'PATCH':
                        $options[RequestOptions::JSON] = $cleanParams;
                        break;
                }
        }
        
        //ADD credentials to path
        //$path = $path."company=".$this->authorization_company."&apiKey=".$this->authorization_token;
        
        $response = $client->request($method, $path, $options);
        
        switch ($response->getStatusCode()) {
            case 200:
                return json_decode($response->getBody(), true);           
            case 500:
                $ex = new 360LException();
                $ex->httpStatusCode = 500;
                $ex->message = 'Internal Server Error: This is an issue with servers processing your request. ';
                throw $ex;           
            default:
                $ex = new 360LException();
                $body = json_decode($response->getBody(), true);
                $ex->httpStatusCode = $response->getStatusCode();
                $ex->360LearningApiErrorCode = $body['ErrorCode'];
                $ex->message = $body['Message'];
                throw $ex;
        }
        
    }
}
