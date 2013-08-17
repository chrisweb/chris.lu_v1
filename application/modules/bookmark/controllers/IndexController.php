<?php

class Bookmark_IndexController extends Zend_Controller_Action
{

    public function init()
	{

		$chrisContext = $this->_helper->getHelper('ChrisContext');
		$chrisContext	->addActionContext('index', 'jquerymobile')
						->initContext('jquerymobile');
        
        $ajaxContext = $this->_helper->getHelper('AjaxContext');
        $ajaxContext->addActionContext('bookmarksbytag', 'json')
                    ->initContext();
		
		parent::init();
		
	}

    public function indexAction()
	{

		$bookmarksModel = new Bookmark_Model_MongoDB_Bookmark();
		
		$keys = array('tags');
		
		$cursor = $bookmarksModel->getList(array(), $keys);
		
		$tags = array();
		
		foreach ($cursor as $bookmarkTag) {
		
			//Zend_Debug::dump($bookmark);
			
			foreach ($bookmarkTag['tags'] as $tag) {
			
                $filterChain = new Zend_Filter();
                $filterChain->addFilter(new Zend_Filter_Alnum())
                            ->addFilter(new Zend_Filter_StringToLower(array('encoding' => 'UTF-8')));
                
                $filteredTag = $filterChain->filter($tag);
                
				if (!array_key_exists($filteredTag, $tags)) {
				
                    $tags[$filteredTag] = $tag;
                    
                }
			
			}
		
		}
		
		$this->view->tags = $tags;
	
    }
    
    public function bookmarksbytagAction() {
        
        $tag = $this->getRequest()->getParam('tag', '');
        
        $bookmarksModel = new Bookmark_Model_MongoDB_Bookmark();
        
        $keys = array('title', 'url');
        
        $cursor = $bookmarksModel->getList(array('tags' => $tag), $keys);
        
        Zend_Debug::dump($cursor);
        
    }

}