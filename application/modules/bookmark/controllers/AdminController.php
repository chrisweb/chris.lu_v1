<?php

class Bookmark_AdminController extends Zend_Controller_Action
{

    public function init()
	{

		$chrisContext = $this->_helper->getHelper('ChrisContext');

		$chrisContext	->addActionContext('index', 'jquerymobile')
						->addActionContext('manage', 'jquerymobile')
						->addActionContext('delete', 'jquerymobile')
						->initContext('jquerymobile');
		
		parent::init();
		
	}

    public function indexAction()
	{

		$bookmarkModel = new Bookmark_Model_MongoDB_Bookmark();
		
		$where = array();
		$keys = array('_id', 'title');
		
		$cursor = $bookmarkModel->getList($where, $keys);
		
		$sort = array('last_update_date' => -1);
		
		// paginator
		$cacheIdentifier = md5('article_list_title_header');
        $adapter = new Chris_Paginator_Adapter_MongoDB($cursor, $cacheIdentifier, $sort);
        $paginator = new Zend_Paginator($adapter);

        $page = (int) $this->getRequest()->getParam('page', 1);

        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
		
		$this->view->paginator = $paginator;
		
		$this->view->routeName = 'bookmarkadminindex';

    }
	
    public function manageAction()
	{

		$id = $this->getRequest()->getParam('id', 0);
		
		$alnumFilter = new Zend_Filter_Alnum();
		
		$filteredId = $alnumFilter->filter($id);
		
		$bookmarkModel = new Bookmark_Model_MongoDB_Bookmark();

        $options = null;
		
		$manageBookmarkForm = new Bookmark_Form_ManageBookmark($options);

        if ($this->getRequest()->isPost()) {

            // get unfiltered POST data
            $formData = $this->getRequest()->getPost();
			
			//Zend_Debug::dump($formData);
			//exit;

            if ($manageBookmarkForm->isValid($formData)) {

                // get form POST values, now with applied filters
                $formData = $manageBookmarkForm->getValues();

				if ($filteredId) $formData['_id'] = $filteredId;
				
				// explode tags
				$formData['tags'] = explode(',', $formData['tags']);
				
				$filteredTags = array();
				
				$filterChain = new Zend_Filter();
				$filterChain->addFilter(new Zend_Filter_StringTrim())
							->addFilter(new Zend_Filter_StringToLower());
				
				foreach($formData['tags'] as $unfilteredTag) {
				
					$filteredTags[] = $filterChain->filter($unfilteredTag);
				
				}
				
				$formData['tags'] = $filteredTags;
				
                //Zend_Debug::dump($formData);
                //exit;

                $response = $bookmarkModel->saveData($formData);

                if ($response) {
				
					$this->_redirect('/bookmark/admin');
					
				} else {
				
					Zend_Debug::dump($response);
				
				}

            } else {

                $manageBookmarkForm->populate($formData);
                $this->view->manageBookmarkForm = $manageBookmarkForm;

            }

        } else {

			if ($filteredId) {
			
				$bookmarkData = $bookmarkModel->getById($filteredId);
				
				//Zend_Debug::dump($bookmarkData);
				
				$bookmarkData['tags'] = implode(', ', $bookmarkData['tags']);
			
				$manageBookmarkForm->populate($bookmarkData);
			
			}
		
            $this->view->manageBookmarkForm = $manageBookmarkForm;

        }

    }
	
	public function deleteAction()
	{
	
		$id = $this->getRequest()->getParam('id', 0);
		
		$alnumFilter = new Zend_Filter_Alnum();
		
		$filteredId = $alnumFilter->filter($id);
		
		$bookmarkModel = new Bookmark_Model_MongoDB_Bookmark();
		
		$keys = array('title');
		
		$bookmark = $bookmarkModel->getById($id, $keys);
		
		//Zend_Debug::dump($bookmark);
		
		$this->view->bookmark = $bookmark;
		
		$options = array();
		
		$deleteBookmarkForm = new Bookmark_Form_DeleteBookmark($options);

        if ($this->getRequest()->isPost()) {

            // get unfiltered POST data
            $formData = $this->getRequest()->getPost();

            if ($deleteBookmarkForm->isValid($formData)) {
			
				//Zend_Debug::dump($formData);
				//exit;
				
				if (array_key_exists('yes', $formData)) {
				
					$bookmarkModel->deleteEntry($id);
					
				}
				
				$this->_redirect('/bookmark/admin');
			
			}
			
		} else {
		
			$this->view->deleteBookmarkForm = $deleteBookmarkForm;
		
		}
	
	}

}