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
		
			//Zend_Debug::dump($bookmarkTag);
			
			foreach ($bookmarkTag['tags'] as $tag) {
                
                //Zend_Debug::dump($tag);
			
                $filterChain = new Zend_Filter();
                $filterChain->addFilter(new Zend_Filter_Alnum())
                            ->addFilter(new Zend_Filter_StringTrim())
                            ->addFilter(new Zend_Filter_StringToLower(array('encoding' => 'UTF-8')));
                
                $filteredTag = $filterChain->filter($tag);
                
				if (!empty($filteredTag) && !array_key_exists($filteredTag, $tags)) {
				
                    $tags[$filteredTag] = $tag;
                    
                }
			
			}
		
		}
        
        sort($tags);
		
		$this->view->tags = $tags;
	
    }
    
    public function bookmarksbytagAction() {
        
        $unfilteredTag = $this->getRequest()->getParam('tag', '');
        
        $filterChain = new Zend_Filter();
        $filterChain->addFilter(new Zend_Filter_StringTrim())
                    ->addFilter(new Zend_Filter_StringToLower());

        $filteredTag = $filterChain->filter($unfilteredTag);
        
        //Zend_Debug::dump($tag, '$tag: ');
        
        $bookmarksModel = new Bookmark_Model_MongoDB_Bookmark();

        // search for the tag that got passed as parameter in the "tags" array
        // that is in mongodb
        $where = array('tags' => $filteredTag);
        $keys = array('title', 'url');
        
        $cursor = $bookmarksModel->getList($where, $keys);
        
        $results = array();
        
        foreach ($cursor as $bookmarkTag) {

            $results[] = array(
                'title' => $bookmarkTag['title'],
                'url' => $bookmarkTag['url']
            );
 
        }
        
        $this->view->results = $results;
        
    }

}