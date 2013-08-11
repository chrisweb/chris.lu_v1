<?php

class Readinglist_IndexController extends Zend_Controller_Action
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

		$readinglistModel = new Readinglist_Model_MongoDB_Readinglist();
		
		$keys = array('title', 'url', 'headline', 'imageUrl', 'imageAlt', 'domain');
		
		$cursor = $readinglistModel->getList(array(), $keys);
		
		// paginator
		$cacheIdentifier = md5('readinglist_list_title');
        $adapter = new Chris_Paginator_Adapter_MongoDB($cursor, $cacheIdentifier);
        $paginator = new Zend_Paginator($adapter);

        $page = (int) $this->getRequest()->getParam('page', 1);

        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(25);
        $paginator->setPageRange(5);
		
		$this->view->paginator = $paginator;
		
		$cursor->sort(array('publish_date' => -1));
		
		$this->view->cursor = $cursor;

    }

}