<?php

/**
 * Chris Service GitHub for Zend Framework 1
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
 * Chris Service GitHub
 */
class Chris_Service_GitHub extends Zend_Rest_Client
{

    /**
     * 
     */
    const API_URL = 'https://api.github.com/';

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

        //Zend_Debug::dump($options);
        //
        if (is_array($options)) {
            $this->setOptions($options);
        }

        // setup http (rest) client
        $this->setLocalHttpClient(clone self::getHttpClient());
        $this->setUri(self::API_URL);
        $this->_localHttpClient->setHeaders('Accept-Charset', 'ISO-8859-1,utf-8');

        //Zend_Debug::dump($this->_localHttpClient);

    }

    /**
     * Set options
     *
     * @param  $options
     * @return Zend_Service_Facebook
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
     * Performs a GitHub query
     * 
     * @return type
     * @throws Zend_Service_Facebook_Exception
     * @throws Zend_Oauth2_Exception
     */
    public function query()
    {
        
        $path = '';
        $query = '';
        
        // retrieve response
        $response = $this->_getResponse($path, $query);

        // check if response is not empty
        if (!is_null($response)) {
            $body   = $response->getBody();
            $status = $response->getStatus();
        } else {
            require_once 'Zend/Service/Facebook/Exception.php';
            throw new Zend_Service_Facebook_Exception('the response we recieved is emtpy');
        }

        //Zend_Debug::dump($body, 'body');
        //exit;

        // convert json response into an array
        $responseAsArray = Zend_Json::decode($body);

        // if status code is different then 200 throw exception
        if ($status != '200') {
            require_once 'Zend/Oauth2/Exception.php';
            throw new Zend_Oauth2_Exception('we recieved an error ('.$status.') as response: '.$responseAsArray['error']['type'].' => '.$responseAsArray['error']['message']);
        }

        return $responseAsArray;

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
    protected function _getResponse($path, array $query = null)
    {
        // Get the URI object and configure it
        if (!$this->_uri instanceof Zend_Uri_Http) {
            require_once 'Zend/Rest/Client/Exception.php';
            throw new Zend_Rest_Client_Exception('URI object must be set before performing call');
        }

        $uri = $this->_uri->getUri();

        if ($path[0] != '/') {
            $path = '/' . $path;
        }

        $this->_uri->setPath($path);

        /**
         * Get the HTTP client and configure it for the endpoint URI. Do this
         * each time because the Zend_Http_Client instance is shared among all
         * Zend_Service_Abstract subclasses.
         */
        $this->_localHttpClient ->resetParameters()
                                ->setUri($this->_uri)
                                ->setParameterGet($query);
        
        return $this->_localHttpClient->request('GET');
    }

}
