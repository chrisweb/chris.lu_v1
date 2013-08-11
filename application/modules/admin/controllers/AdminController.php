<?php

class Admin_AdminController extends Zend_Controller_Action
{

    public function init()
	{

		$chrisContext = $this->_helper->getHelper('ChrisContext');

		$chrisContext	->addActionContext('index', 'jquerymobile')
						->initContext('jquerymobile');
		
		parent::init();
		
	}

    public function indexAction()
	{


    }

}