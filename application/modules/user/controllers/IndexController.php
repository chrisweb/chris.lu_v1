<?php

class User_IndexController extends Zend_Controller_Action
{

    public function init()
	{

        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('needauthentification', 'json')
                    ->initContext();
		
		parent::init();
		
	}
    
    /**
     * user log in page
     */
    public function loginAction()
	{

        $form = new User_Form_LoginForm();

        if ($this->_request->isPost()) {

            $formData = $this->_request->getPost();
			
			$userConfiguration = Zend_Registry::get('UserConfiguration');
			
			$failed = false;

            if ($form->isValid($formData)) {
	
				$formData = $form->getValues();
	
				$auth = Zend_Auth::getInstance();
				
				// retrieve a mongodb collection
				$frontController = Zend_Controller_Front::getInstance();

				$bootstrap = $frontController->getParam('bootstrap');
				
				if ($bootstrap->hasResource('DatabaseConnection')) {
				
					$mongoConnection = $bootstrap->getResource('DatabaseConnection');
					
				}
				
				$userCollection = $mongoConnection->selectCollection('user');
				
				// create authAdapter instance
				$authAdapter = new Chris_Auth_Adapter_MongoDB($userCollection);
                $authAdapter	->setIdentityKey('username');
                $authAdapter	->setCredentialKey('password');
                $authAdapter	->setIdentity($formData['username']);
                $authAdapter	->setCredential($formData['password']);
				$authAdapter	->setSalt($userConfiguration->authentification->password->salt);

				$result = $auth->authenticate($authAdapter);
				
				//Zend_Debug::dump($result);

				if ($result->isValid()) {

					$data = $authAdapter->getResultObject(null, 'password');
					
					//Zend_Debug::dump($data, '$data');
					//exit;
					
					$auth->getStorage()->write($data);
				
					if ($formData['rememberme']) {
					
						$name = $userConfiguration->authentification->rememberme->cookie->name;
                        $content = $userConfiguration->authentification->rememberme->cookie->content;
                        $expireTime = time()+$userConfiguration->authentification->rememberme->cookie->lifetime;
                        $cookiePath = $userConfiguration->authentification->rememberme->cookie->path;
						$websiteDomain = $userConfiguration->authentification->rememberme->cookie->domain;
						$secureCookie = $userConfiguration->authentification->rememberme->cookie->secure;
						$httponlyCookie = $userConfiguration->authentification->rememberme->cookie->httponly;
					
						setcookie($name, $content, $expireTime, $cookiePath, $websiteDomain, $secureCookie, $httponlyCookie);
					
					}
					
					// TODO: save lastlogin date in database

					$this->_redirect('/admin');
				
				} else {
				
					$failed = true;
				
				}
				
			} else {
				
				$failed = true;
			
			}
			
			if ($failed) {
			
				$form->populate(array());
				$this->view->form = $form;
			
				$this->view->failed = true;
			
			}
			
		} else {
		
			$this->view->form = $form;
		
		}
	
    }
	
    /**
     * redirects here if user is authentificated but blocked by acl rule
     */
	public function missingrightsAction()
	{
	
		
	
	}
    
    /**
     * 
     * redirects here if user does ajax request but request is missing
     * authentification data
     * 
     */
    public function needauthentificationAction()
	{
	
		$this->view->errorMessage = 'Missing User Authentification Data';
	
	}

}