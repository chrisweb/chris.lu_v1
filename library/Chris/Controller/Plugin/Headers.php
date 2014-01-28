<?php

class Chris_Controller_Plugin_Headers extends Zend_Controller_Plugin_Abstract {

    /**
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function postDispatch(Zend_Controller_Request_Abstract $request) {
	
		//Zend_Debug::dump($request->getModuleName(), 'module name');
		//Zend_Debug::dump($request->getControllerName(), 'controller name');
		//Zend_Debug::dump($request->getActionName(), 'action name');
		
        //$response = new Zend_Controller_Response_Http;
		$response = $this->getResponse();

        // disallow loading content in frames from external sources, to prevent
        // clickjacking attacks, comment out these lines or restrict urls that
        // use it, if you need to enable iframes/frames with content from
        // external sources
		// XXS Protection header for IE 8 and above
		// you check your headers with http://redbot.org/
		// READ: http://recxltd.blogspot.co.uk/2012/03/seven-web-server-http-headers-that.html
		// READ: http://www.tumblr.com/tagged/hypertext-preprocessor
		// http://dustint.com/post/25/cache-control-with-zend-framework
		// http://www.zfsnippets.com/snippets/view/id/67/notmodified-cache-controller-plugin
		// http://redbot.org/?uri=http%3A%2F%2Fgoogle.com
        if (APPLICATION_ENV === 'production') {
            $response->setHeader('X-Frame-Options', 'DENY');
			$response->setHeader('X-XSS-Protection', '1; mode=block');
            $response->setHeader('Strict-Transport-Security', 'DENY');
            $response->setHeader('X-Content-Type-Options', 'nosniff');
            $response->setHeader('X-Chrisweb', 'Hello World ;)');
        }
		
		// robots header tag
		// https://developers.google.com/webmasters/control-crawl-index/docs/robots_meta_tag?hl=fr
		if (substr($request->getControllerName(), 0, 5) === 'admin'
		|| substr($request->getActionName(), 0, 5) === 'login') {
		
			$response->setHeader('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet');

		}
		
		//$frontController = Zend_Controller_Front::getInstance();
		//$frontController->setResponse($response);
	
    }

}
