<?php

/**
 * Chris Service Youtube for Zend Framework 1
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 */

/**
 * @see Zend_Rest_Client
 */
require_once 'Zend/Rest/Client.php';

/**
 * @see Zend_Json
 */
require_once 'Zend/Json.php';

/**
 * @see Zend_Oauth2
 */
require_once 'Chrisweb/Oauth2.php';

/**
 * Chris Service Youtube
 */
class Chris_Service_Youtube extends Zend_Rest_Client
{

    /**
     * 
     */
    const API_URL = 'https://www.googleapis.com/youtube/v3';
    
    /**
     *
     * @var type 
     */
    protected $_path = '';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct($options = null)
    {
        // if options are instance of zend config convert them to array
        if ($options instanceof Zend_Config) {
            $options = $options->toArray();
        }

        //Zend_Debug::dump($options, '$options: ');
        //
        if (is_array($options)) {
            $this->setOptions($options);
        }

        // setup http (rest) client
        $this->setLocalHttpClient(clone self::getHttpClient());
        $this->setUri(self::API_URL);
        $this->_localHttpClient->setHeaders('Accept-Charset', 'ISO-8859-1,utf-8');

        //Zend_Debug::dump($this->_localHttpClient, '$this->_localHttpClient: ');

    }

    /**
     * Set options
     *
     * @param  $options
     * @return Zend_Service_Youtube
     */
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            $method = 'set' . $key;
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }

    /**
     * Set local HTTP client as distinct from the static HTTP client
     * as inherited from Zend_Rest_Client.
     *
     * @param Zend_Http_Client $client
     * @return self
     */
    public function setLocalHttpClient(Zend_Http_Client $client)
    {
        $this->_localHttpClient = $client;
        return $this;
    }

    /**
     *
     * @return <type>
     */
    public function getLocalHttpClient()
    {
        return $this->_localHttpClient;
    }

    /**
     *
     */
    public function clearLocalHttpClient()
    {
        $this->_localHttpClient = null;
    }
    
    /**
     * 
     * @param String $path
     * @return \Chris_Service_Youtube
     */
    public function setPath($path)
    {
        if ($path[0] !== '/') {
            $path = '/' . $path;
        }
        
        $this->_path = $path;
        
        return $this;
    }

    /**
     * 
     * @return type
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * 
     */
    public function clearPath()
    {
        $this->_path = null;
    }

    /**
     * 
     * Performs a Youtube query
     * 
     * @return type
     * @throws Zend_Service_Youtube_Exception
     * @throws Zend_Oauth2_Exception
     */
    public function query()
    {
        // retrieve response
        $response = $this->_getResponse();

        // check if response is not empty
        if (!is_null($response)) {
            
            if (!is_string($response)) {
                
                $body   = $response->getBody();
                $status = $response->getStatus();
                
            } else {
                require_once 'Chris/Service/Youtube/Exception.php';
                throw new Chris_Service_Youtube_Exception($response);
            }

        } else {
            require_once 'Chris/Service/Youtube/Exception.php';
            throw new Chris_Service_Youtube_Exception('the response we recieved is emtpy');
        }

        //Zend_Debug::dump($body, 'body: ');exit;
        
        // convert json response into an array
        try {
            $result = Zend_Json::decode($body);
        } catch (Exception $exception) {
            require_once 'Chris/Service/Youtube/Exception.php';
            throw new Chris_Service_Youtube_Exception('JSON decoding failed: '.$exception->getMessage());
        }

        //Zend_Debug::dump($responseAsArray, '$responseAsArray: ');exit;

        // if status code is different then 200 throw exception
        if ($status != '200') {
            require_once 'Chris/Service/Youtube/Exception.php';
            throw new Chris_Service_Youtube_Exception('we recieved an error ('.$status.') as response: '.serialize($result));
        }

        return $result;

    }

    /**
     * 
     * Performs an HTTP GET request to the $path.
     * 
     * @param string $path
     * @param array $query
     * @return type
     * @throws Zend_Rest_Client_Exception
     */
    protected function _getResponse()
    {
        // check if the path was set
        if (empty($this->_path)) {
            require_once 'Chris/Service/Youtube/Exception.php';
            throw new Chris_Service_Youtube_Exception('Path must be set before performing call, use setPath() to set one.');
        }
        
        //Zend_Debug::dump(self::API_URL.$this->_path, 'self::API_URL.$this->_path: ');
        
        /**
         * Get the HTTP client and configure it for the endpoint URI. Do this
         * each time because the Zend_Http_Client instance is shared among all
         * Zend_Service_Abstract subclasses.
         */
        $this->_localHttpClient ->resetParameters()
                                ->setUri(self::API_URL.$this->_path);
                                //->setParameterGet();
        
        //Zend_Debug::dump($this->_localHttpClient, '$this->_localHttpClient: ');exit;
        
        try {
            $response = $this->_localHttpClient->request('GET');
        } catch (Exception $exception) {
            $response = $exception->getMessage();
        }
        
        return $response;
    }

}
