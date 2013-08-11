<?php

class Bookmark_IndexController extends Zend_Controller_Action
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

		$projectsModel = new Bookmark_Model_MongoDB_Bookmark();
		
		$keys = array('title', 'url', 'tags');
		
		$cursor = $projectsModel->getList(array(), $keys);
		
		$bookmarks = array();
		
		foreach ($cursor as $bookmark) {
		
			//Zend_Debug::dump($bookmark);
			
			foreach ($bookmark['tags'] as $tag) {
			
				if (!array_key_exists($tag, $bookmarks)) $bookmarks[$tag] = array();
				
				$bookmarks[$tag][] = array('title' => $bookmark['title'], 'url' => $bookmark['url']);
			
			}
		
		}
		
		$this->view->bookmarks = $bookmarks;
	
    }

}