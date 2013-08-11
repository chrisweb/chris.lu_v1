<?php

/**
 * @see Zend_Controller_Action_Helper_Abstract
 */
require_once 'Zend/Controller/Action/Helper/ContextSwitch.php';

class Chris_Controller_Action_Helper_ChrisContext extends Zend_Controller_Action_Helper_ContextSwitch
{

    /**
     * Constructor
     *
     * Add jquerymobile context
     *
     * @return void
     */
    public function __construct()
    {
	
        parent::__construct();
		
        $this->addContext('jquerymobile', array('suffix' => null));
		
    }

    /**
     * Initialize jquerymobile context switching
     *
     * Checks for XHR requests; if detected, attempts to perform context switch.
     *
     * @param  string $format
     * @return void
     */
    public function initContext($format = null)
    {
	
        $this->_currentContext = null;

        $request = $this->getRequest();
		
		//Zend_Debug::dump(method_exists($request, 'isXmlHttpRequest'));
		//Zend_Debug::dump($this->getRequest()->isXmlHttpRequest());
		
        if (!method_exists($request, 'isXmlHttpRequest') ||
            !$this->getRequest()->isXmlHttpRequest()) {
			
			//Zend_Debug::dump('exit');
			
            return;
        }
		
		$this->setAutoDisableLayout(false);
		
		$layout = Zend_Layout::getMvcInstance();
		$layout->setLayout('jquerymobile');

        return parent::initContext($format);
    }
	
}

