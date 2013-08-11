<?php

/**
 * @see Zend_Auth_Adapter_Interface
 */
require_once 'Zend/Auth/Adapter/Interface.php';

/**
 * @see Zend_Auth_Result
 */
require_once 'Zend/Auth/Result.php';


/**
 * @category   Zend
 * @package    Zend_Auth
 * @subpackage Adapter
 * @copyright  Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Chris_Auth_Adapter_MongoDB implements Zend_Auth_Adapter_Interface
{

	protected $_collection = null;
	protected $_identityKey = null;
	protected $_credentialKey = null;
	protected $_salt = null;
	protected $_identity = null;
	protected $_credential = null;
	protected $_authenticateResultInfo = null;
	protected $_result;

    public function __construct(MongoCollection $collection = null, $identityKey = null,
                                $credentialKey = null, $salt = null)
    {
	
        if ($collection !== null) {
            $this->setCollection($collection);
        }
 
        if ($identityKey !== null) {
            $this->setIdentityKey($identityKeyPath);
        }
 
        if ($credentialKey !== null) {
            $this->setCredentialKey($credentialKeyPath);
        }
 
        if ($salt !== null) {
            $this->setSalt($salt);
        }
    }
	
    public function setCollection(MongoCollection $collection = null)
    {
	
        $this->_collection = $collection;
		
		if ($collection === null) {
		
			require_once 'Zend/Auth/Adapter/Exception.php';
			throw new Zend_Auth_Adapter_Exception('No MongoDB collection present');
			
		}
		
	}
	
    public function setIdentityKey($identityKey)
    {
	
        $this->_identityKey = $identityKey;
		
        return $this;
		
    }
	
    public function setCredentialKey($credentialKey)
    {
	
        $this->_credentialKey = $credentialKey;
		
        return $this;
		
    }
	
    public function setSalt($salt)
    {
	
        $this->_salt = $salt;
		
        return $this;
		
    }
	
    public function setIdentity($value)
    {
	
        $this->_identity = $value;
		
        return $this;
		
    }

    public function setCredential($credential)
    {
	
        $this->_credential = $credential;
		
        return $this;
		
    }
	
    public function getResultObject($returnKeys = null, $omitKeys = null)
    {
	
        if (!$this->_result) {
            return false;
        }
		
        $returnObject = new stdClass();

        if ($returnKeys !== null) {

            $availableKeys = array_keys($this->_result);
			
            foreach ( (array) $returnKeys as $returnKey) {
                if (in_array($returnKey, $availableKeys)) {
                    $returnObject->{$returnKey} = $this->_result[$returnKey];
                }
            }
            return $returnObject;

        } elseif ($omitKeys !== null) {

            $omitKeys = (array) $omitKeys;
            foreach ($this->_result as $resultKey => $resultValue) {
                if (!in_array($resultKey, $omitKeys)) {
                    $returnObject->{$resultKey} = $resultValue;
                }
            }
            return $returnObject;

        } else {

            foreach ($this->_result as $resultKey => $resultValue) {
                $returnObject->{$resultKey} = $resultValue;
            }
            return $returnObject;

        }
	
	}

    /**
     * authenticate() - defined by Zend_Auth_Adapter_Interface.  This method is called to
     * attempt an authentication.  Previous to this call, this adapter would have already
     * been configured with all necessary information to successfully connect to a database
     * table and attempt to find a record matching the provided identity.
     *
     * @throws Zend_Auth_Adapter_Exception if answering the authentication query is impossible
     * @return Zend_Auth_Result
     */
    public function authenticate()
    {

		$this->_authenticateSetup();
	
		$cursor = $this->_collection->find(array($this->_identityKey => $this->_identity));
		
		$this->_authenticateResultInfo['identity'] = $this->_identity;
	
        if ($cursor->count() === 0) {
		
            $this->_authenticateResultInfo['code'] = Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND;
            $this->_authenticateResultInfo['messages'][] = 'A record with the supplied identity could not be found.';
            return $this->_authenticateCreateAuthResult();
			
        } elseif ($cursor->count() > 1) {
		
            $this->_authenticateResultInfo['code'] = Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS;
            $this->_authenticateResultInfo['messages'][] = 'More than one record matches the supplied identity.';
            return $this->_authenticateCreateAuthResult();
			
        } else {
		
			$user = $cursor->getNext();
			
			//Zend_Debug::dump($resultIdentity);
		
			if ($user[$this->_credentialKey] !== md5($this->_credential.$this->_salt)) {

				$this->_authenticateResultInfo['code'] = Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID;
				$this->_authenticateResultInfo['messages'][] = 'Supplied credential is invalid.';
				return $this->_authenticateCreateAuthResult();
			
			}
			
		}
		
		$this->_result = $user;

		$this->_authenticateResultInfo['code'] = Zend_Auth_Result::SUCCESS;
        $this->_authenticateResultInfo['messages'][] = 'Authentication successful.';
        return $this->_authenticateCreateAuthResult();
		
    }
	
    protected function _authenticateSetup()
    {
        $exception = null;

        if ($this->_collection == '') {
            $exception = 'A collection must be supplied prior to authentication.';
        } elseif ($this->_identityKey == '') {
            $exception = 'An identity key must be supplied prior to authentication.';
        } elseif ($this->_credentialKey == '') {
            $exception = 'A credential key must be supplied prior to authentication.';
        } elseif ($this->_identity == '') {
            $exception = 'A value for the identity was not provided prior to authentication.';
        } elseif ($this->_credential == '') {
            $exception = 'A credential value was not provided prior to authentication.';
		} elseif ($this->_salt == '') {
			$exception = 'A salt value was not provided prior to authentication.';
        }

        if ($exception !== null) {
            /**
             * @see Zend_Auth_Adapter_Exception
             */
            require_once 'Zend/Auth/Adapter/Exception.php';
            throw new Zend_Auth_Adapter_Exception($exception);
        }

        $this->_authenticateResultInfo = array(
            'code'     => Zend_Auth_Result::FAILURE,
            'identity' => $this->_identity,
            'messages' => array()
            );

        return true;
    }
	
    protected function _authenticateCreateAuthResult()
    {
        return new Zend_Auth_Result(
            $this->_authenticateResultInfo['code'],
            $this->_authenticateResultInfo['identity'],
            $this->_authenticateResultInfo['messages']
            );
    }

}
