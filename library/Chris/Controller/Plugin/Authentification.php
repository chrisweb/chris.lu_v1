<?php

class Chris_Controller_Plugin_Authentification extends Zend_Controller_Plugin_Abstract
{

	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
	{
	
		//Zend_Debug::dump($request->getModuleName());
	
		$userConfiguration = Zend_Registry::get('UserConfiguration');
		
		$name = $userConfiguration->authentification->rememberme->cookie->name;
		$content = $userConfiguration->authentification->rememberme->cookie->content;

		$frontController = Zend_Controller_Front::getInstance();
		$bootstrap = $frontController->getParam('bootstrap');
		$isAjax = $bootstrap->getResource('AjaxDetection');
		
		//Zend_Debug::dump($isAjax, '$isAjax');

		if (isset($_COOKIE[$name]) && !$isAjax && $_COOKIE[$name] == $content) {
		
			$authRememberMeLifetime = $userConfiguration->authentification->loggedin->session->lifetime;
		
			Zend_Session::rememberUntil($authRememberMeLifetime);
		
			$content = '';
			$expireTime = time()-$userConfiguration->authentification->rememberme->cookie->lifetime;
			$cookiePath = $userConfiguration->authentification->rememberme->cookie->path;
			$websiteDomain = $userConfiguration->authentification->rememberme->cookie->domain;
			$secureCookie = $userConfiguration->authentification->rememberme->cookie->secure;
			$httponlyCookie = $userConfiguration->authentification->rememberme->cookie->httponly;
		
			setcookie($name, $content, $expireTime, $cookiePath, $websiteDomain, $secureCookie, $httponlyCookie);
		
		}
	
	}

}