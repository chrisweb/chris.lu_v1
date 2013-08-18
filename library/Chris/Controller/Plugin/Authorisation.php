<?php

class Chris_Controller_Plugin_Authorisation extends Zend_Controller_Plugin_Abstract
{

	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
	{
	
		// checkout:
		// http://www.youtube.com/watch?v=1lD-Hv5MYxM
		// http://www.youtube.com/watch?v=PALEC6XqCZc
		// http://de.slideshare.net/wimg/creating-fast-dynamic-acls-in-zend-framework-zend-webinar-8337996
		// http://stackoverflow.com/questions/5209671/zend-framework-need-typical-example-of-acl
		// http://zendguru.wordpress.com/2008/11/05/zend-framework-acl-with-example/
		// http://framework.zend.com/manual/1.12/en/zend.acl.refining.html

		$auth = Zend_Auth::getInstance();
		$acl = new Zend_Acl();
		
		$userConfiguration = Zend_Registry::get('UserConfiguration');
		
		$defaultRole = $userConfiguration->authentification->default->role;
        
		$identity = $auth->getIdentity();

		if (isset($identity->role)) {
			$role = strtolower($identity->role);
		} else {
			$role = $defaultRole;
		}

		// set roles
		$acl->addRole(new Zend_Acl_Role($defaultRole));
		$acl->addRole(new Zend_Acl_Role('admin'), $defaultRole);
        
        // get roles from database and add them
		/*if (is_array($rolesData)) {

            foreach($rolesData as $role) {

                if (!$this->_acl->hasRole($role)) $this->_acl->addRole(new Zend_Acl_Role($role));

            }

        }*/
		
		// set resources
		$acl->add(new Zend_Acl_Resource('admin')); // admin controllers
		$acl->add(new Zend_Acl_Resource('index')); // index controllers
        $acl->add(new Zend_Acl_Resource('error')); // error controller
		
        /*if (is_array($resourcesData)) {

            foreach($resourcesData as $resource) {

                //Zend_Debug::dump($resource);

                if (!$this->_acl->has($resource)) $this->_acl->add(new Zend_Acl_Resource($resource));

            }

        }*/
		
		// set rules
		$acl->allow($defaultRole, 'index');
        $acl->allow($defaultRole, 'error');
		$acl->allow('admin', 'admin');
		
		$resource = $request->getControllerName();
        
        //Zend_Debug::dump($request->getModuleName(), '$request->getModuleName(): ');
        //Zend_Debug::dump($request->getActionName(), '$request->getActionName(): ');
        //Zend_Debug::dump($request->getControllerName(), '$request->getControllerName(): ');
        //Zend_Debug::dump($request->getParams(), '$request->getParams(): ');
        
        //Zend_Debug::dump($resource);
        //Zend_Debug::dump($request);exit;
		
		if ($acl->has($resource)) {
		
			if (!$acl->isAllowed($role, $resource)) {
			
				if ($auth->hasIdentity() && !$isAjax) {
				
					$this->_response->setRedirect('/user/missingrights', 403);
				
				} else {
			
                    $frontController = Zend_Controller_Front::getInstance();

                    $bootstrap = $frontController->getParam('bootstrap');
                    
                    $isAjax = false;

                    if ($bootstrap->hasResource('AjaxDetection')) {
                        
                          $isAjax = $bootstrap->getResource('ajaxDetection');
                          
                    }
                    
                    //Zend_Debug::dump($isAjax, '$isAjax: ');exit;
                    
                    // if its an ajax request use user module "needauthentification" 
                    // action json response else redirect to login page
                    if ($isAjax) {

                        $request->setModuleName('user');
                        $request->setControllerName('index');
                        $request->setActionName('needauthentification');
                        
                    } else {
                        
                        $flashMessenger = new Zend_Controller_Action_Helper_FlashMessenger();
                        $flashMessenger->setNamespace('authorisationErrors');
                        $flashMessenger->addMessage('AUTH_MUST_LOGIN');

                        $this->_response->setRedirect('/user/login');
                        
                    }
					
				}
			
			}
		
		} else {

			if (APPLICATION_ENV == 'production') {
		
				throw new Zend_Controller_Action_Exception('Resource could not be found.', 404);
				
			} else {
			
				throw new Exception('Resource could not be found. Missing resource: '.$resource, 500);
			
			}
		
		}
	
	}

}