<?php

class Homepage_IndexController extends Zend_Controller_Action
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

		$articleModel = new Article_Model_MongoDB_Article();
		
		$where = array('status' => '2');
		$keys = array('_id', 'title', 'header', 'tags', 'image', 'image_alt');
		
		$cursor = $articleModel->getList($where, $keys);
		
		$sort = array('publish_date' => -1);
		
		// paginator
		$cacheIdentifier = md5('article_list_title_header_tags_image_imagealt');
        $adapter = new Chris_Paginator_Adapter_MongoDB($cursor, $cacheIdentifier, $sort);
        $paginator = new Zend_Paginator($adapter);

        $page = (int) $this->getRequest()->getParam('page', 1);

        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
		
		$this->view->paginator = $paginator;
		
		$this->view->routeName = 'homepagepages';

    }
	
	public function testAction()
	{
	
		/*$testModel = new Homepage_Model_MongoDB_Test();
		
		//$data = array('title' => '', 'header' => '', 'body' => '', 'tags' => '');
		$data = array('title' => '');
		
		//$response = $testModel->insertData($data);
		//Zend_Debug::dump($response, 'insert $response');
		
		//$response = $testModel->getData();
		//Zend_Debug::dump($response, 'get $response');
		
		//$testModel->getList();
		
		$id = '5072a2535051868414000000';
		$data = array('title' => 'bar');
		$options = array('safe' => true);
		// if overwrite false, keep db entry as is, only replace fields that get passed
		// if overwrite true, clear all fields (key and value), then readd fields that got passed
		$response = $testModel->updateData($id, $data, $options, false);
		
		$testModel->getList();
		
		$form = new Homepage_Form_Test();
		
		if ($this->getRequest()->isPost()) {
		
			$formData = $this->getRequest()->getPost();
			
			if ($form->isValid($formData)) {
			
				$formData = $form->getValues(true);
				
				Zend_Debug::dump($formData, 'submitted form data');
				
				// explode tags
				if (array_key_exists('tags', $formData)) {
				
					$formData['tags'] = explode(',', $formData['tags']);
					
					$filter = new Zend_Filter_StringTrim();
					
					$tags = array();

					foreach ($formData['tags'] as $tag) {
					
						$tag = $filter->filter($tag);
						
						$tags[] = array('id' => md5($tag), 'name' => $tag);
					
					}
					
					$formData['tags'] = $tags;
				
				}
				
				Zend_Debug::dump($formData, 'form data after transforming tags');
			
			} else {
			
				$form->populate($formData);
                $this->view->form = $form;
			
			}
		
		} else {
		
			//if ($id) {
			
				
			
			//}
		
			$this->view->form = $form;
		
		}*/
	
	}

}