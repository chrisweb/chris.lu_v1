<?php

class Readinglist_AdminController extends Zend_Controller_Action {

    public function init()
	{
					
		$chrisContext = $this->_helper->getHelper('ChrisContext');

		$chrisContext	->addActionContext('index', 'jquerymobile')
						->addActionContext('manage', 'jquerymobile')
						->addActionContext('delete', 'jquerymobile')
						->initContext('jquerymobile');
						
		$ajaxContext = $this->_helper->getHelper('AjaxContext');

		$ajaxContext->addActionContext('autopopulate', 'json')
					->initContext('json');
		
		parent::init();
		
	}

    public function indexAction()
	{

		$readinglistModel = new Readinglist_Model_MongoDB_Readinglist();
		
		$keys = array('_id', 'title');
		
		$cursor = $readinglistModel->getList(array(), $keys);
		
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
		
		$this->view->routeName = 'readinglistadminindex';

    }
	
    public function manageAction()
	{

		$id = $this->getRequest()->getParam('id', 0);
		
		$alnumFilter = new Zend_Filter_Alnum();
		
		$filteredId = $alnumFilter->filter($id);
		
		$readinglistModel = new Readinglist_Model_MongoDB_Readinglist();

        $options = null;
		
		$manageReadinglistForm = new Readinglist_Form_ManageReadinglist($options);

        if ($this->getRequest()->isPost()) {

            // get unfiltered POST data
            $formData = $this->getRequest()->getPost();
			
			//Zend_Debug::dump($formData);
			//exit;

            if ($manageReadinglistForm->isValid($formData)) {

                // get form POST values, now with applied filters
                $formData = $manageReadinglistForm->getValues();

				if ($filteredId) $formData['_id'] = $filteredId;
				
                //Zend_Debug::dump($formData);
                //exit;

                $response = $readinglistModel->saveData($formData);

                if ($response) {
				
					$this->_redirect('/readinglist/admin');
					
				} else {
				
					//Zend_Debug::dump($response);
					$manageReadinglistForm->populate($formData);
					$this->view->manageReadinglistForm = $manageReadinglistForm;
				
				}

            } else {

                $manageReadinglistForm->populate($formData);
                $this->view->manageReadinglistForm = $manageReadinglistForm;

            }

        } else {

			if ($filteredId) {
			
				$readinglistData = $readinglistModel->getById($filteredId);
				
				//Zend_Debug::dump($readinglistData);
			
				$manageReadinglistForm->populate($readinglistData);
			
			}
		
            $this->view->manageReadinglistForm = $manageReadinglistForm;

        }

    }
	
	public function deleteAction()
	{
	
		$id = $this->getRequest()->getParam('id', 0);
		
		$alnumFilter = new Zend_Filter_Alnum();
		
		$filteredId = $alnumFilter->filter($id);
		
		$readinglistModel = new Readinglist_Model_MongoDB_Readinglist();
		
		$keys = array('title');
		
		$readinglist = $readinglistModel->getById($id, $keys);
		
		//Zend_Debug::dump($readinglist);
		
		$this->view->readinglist = $readinglist;
		
		$options = array();
		
		$deleteReadinglistForm = new Readinglist_Form_DeleteReadinglist($options);

        if ($this->getRequest()->isPost()) {

            // get unfiltered POST data
            $formData = $this->getRequest()->getPost();

            if ($deleteReadinglistForm->isValid($formData)) {
			
				//Zend_Debug::dump($formData);
				//exit;
				
				if (array_key_exists('yes', $formData)) {
				
					$readinglistModel->deleteEntry($id);
					
				}
				
				$this->_redirect('/readinglist/admin');
			
			}
			
		} else {
		
			$this->view->deleteReadinglistForm = $deleteReadinglistForm;
		
		}
	
	}
	
	public function autopopulateAction()
	{
	
		$bootstrap = $this->getInvokeArg('bootstrap');
		$isXHR = $bootstrap->getResource('AjaxDetection');
	
		if ($this->getRequest()->isPost() && $isXHR) {
		
			$dom = new DOMDocument();
			
			/*$dom->encoding = 'UTF-8';
			$dom->preserveWhiteSpace = false; 
			$dom->formatOutput = true;
			$dom->strictErrorChecking = false;*/
			
			//$url = 'http://davidwalsh.name/firefox-html';
			//$url = 'http://www.golem.de/news/samsung-ativ-smart-pc-im-test-windows-8-und-atom-im-tablet-das-beste-aus-beiden-welten-1210-95423.html';
			
			$url = $this->getRequest()->getPost('articleurl');

			//Zend_Debug::dump($url, '$url');
			
			if (!empty($url)) {
			
				$filterChainUrl = new Zend_Filter();
				$filterChainUrl	->addFilter(new Zend_Filter_StringTrim())
								->addFilter(new Zend_Filter_StripTags())
								->addFilter(new Zend_Filter_StripNewlines());
							
				$filterChainText = new Zend_Filter();
				$filterChainText->addFilter(new Zend_Filter_StringTrim())
								->addFilter(new Zend_Filter_StripTags());
				
				$url = $filterChainUrl->filter($url);
				
				//Zend_Debug::dump($url, '$url');
				
				$urlArray = parse_url($url);
				
				//Zend_Debug::dump($urlArray, '$urlArray');
				
				$domain = '';
				
				if (is_array($urlArray) && array_key_exists('scheme', $urlArray) && array_key_exists('host', $urlArray)) {
				
					$domain = $urlArray['scheme'].'://'.$urlArray['host'];
					
				}
				
				$htmlDocument = file_get_contents($url);
				
				//Zend_Debug::dump($htmlDocument, '$htmlDocument');
				
				//$dom->normalizeDocument();
				
				@$dom->loadHTML($htmlDocument);

				$metaTags = $dom->getElementsByTagName('meta');
				
				$websiteData = array();
				
				$websiteData['domain'] = $domain;
				
				foreach ($metaTags as $element) {
				
					//Zend_Debug::dump($element);
					
					if ($element->hasAttributes()) {
					
						$attributes = $element->attributes;
						
						foreach($attributes as $attribute) {
						
							//Zend_Debug::dump($attribute->nodeName);
							//Zend_Debug::dump($attribute->nodeValue);
							
							$attributeValue = $element->getAttribute('content');
							
							if (!empty($attributeValue)) {
							
								switch ($attribute->nodeValue) {
									case 'og:image':
										$imageUrl = $filterChainUrl->filter($attributeValue);
										
										$thumbnailPath = $this->saveFile($imageUrl, $domain, 'thumbnail');
										
										if ($thumbnailPath) {
										
											$websiteData['image'] = $thumbnailPath;
											
										}
										
										//Zend_Debug::dump($websiteData, '$websiteData');
										
										break;
									case 'og:title':
										//Zend_Debug::dump(mb_detect_encoding(attributeValue));
										$websiteData['title'] = html_entity_decode($filterChainText->filter($attributeValue), ENT_QUOTES);
										break;
									case 'description':
										//Zend_Debug::dump(mb_detect_encoding(attributeValue));
										$websiteData['description'] = html_entity_decode($filterChainText->filter($attributeValue), ENT_QUOTES);
										break;
									case 'og:description':
										//Zend_Debug::dump(mb_detect_encoding(attributeValue));
										$websiteData['description'] = html_entity_decode($filterChainText->filter($attributeValue), ENT_QUOTES);
										break;
								}
								
							}
							
						}
							
					}
				
				}
				
				$linkTags = $dom->getElementsByTagName('link');
				
				foreach ($linkTags as $element) {
				
					//Zend_Debug::dump($element);
					
					if ($element->hasAttributes()) {
					
						$attributes = $element->attributes;
						
						foreach($attributes as $attribute) {
						
							$attributeValue = $element->getAttribute('href');
							
							$attributeValue = $filterChainUrl->filter($attributeValue);
							
							if ($attribute->nodeValue === 'shortcut icon') {

								$faviconUrl = $filterChainUrl->filter($attributeValue);
								
								$faviconPath = $this->saveFile($faviconUrl, $domain, 'favicon');
								
								if ($faviconPath) {
								
									$websiteData['favicon'] = $faviconPath;
									
								}
							
							}
							
						}
							
					}
				
				}
				
				if (!array_key_exists('title', $websiteData)) {
				
					$titleDom = $dom->getElementsByTagName('title');
					
					//Zend_Debug::dump($titleDom);
				
					if ($titleDom->length > 0) {
					
						$titleTag = $titleDom->item(0)->textContent;
					
						//Zend_Debug::dump(mb_detect_encoding($dom->getElementsByTagName('title')->item(0)->textContent));
					
						$websiteData['title'] = html_entity_decode($filterChainText->filter($titleTag), ENT_QUOTES);
						
					}
				
				}
				
				$linkTags = $dom->getElementsByTagName('link');
				
				$websiteData['error'] = 0;
				
				//Zend_Debug::dump($websiteData, '$websiteData');
			
				$this->view->websiteData = $websiteData;
				
			} else {
			
				$this->view->websiteData = array('error' => 1, 'errorMessage' => 'EMPTY_QUERY_PARAMETER');
			
			}
		
		} else {
		
			$this->view->websiteData = array('error' => 1, 'errorMessage' => 'UNKNOWN_REQUEST');
		
		}
	
	}
	
	protected function saveFile($fileUrl, $domain, $type = 'thumbnail')
	{
	
		$directoryHelper = $this->_helper->Directory;
		$fileHelper = $this->_helper->File;
		$imageHelper = $this->_helper->Image;
		
		//Zend_Debug::dump($fileUrl, '$fileUrl before');
		
		// remove get parameters
		$fileUrlExploded = explode('?', $fileUrl);
		
		$fileUrl = $fileUrlExploded[0];
		
		// if the first slash is missing but URL does not start with http, we add first backslash
		if (substr($fileUrl, 0, 1) !== '/' && substr($fileUrl, 0, 4) !== 'http') {
		
			$fileUrl = '/'.$fileUrl;
		
		}
		
		// if url has doubel backslash we need to add scheme
		if (substr($fileUrl, 0, 2) === '//') {
		
			$fileUrl = 'http://'.substr($fileUrl, 2, strlen($fileUrl)-2);
		
		}
		
		// if the URL start with a slash add the missing domain and scheme
		if (substr($fileUrl, 0, 1) === '/') {
		
			$fileUrl = $domain.$fileUrl;
		
		}
		
		//Zend_Debug::dump($fileUrl, '$fileUrl after');
	
		$fileContent = file_get_contents($fileUrl);
		
		if ($fileContent) {
			
			$bootstrap = $this->getInvokeArg('bootstrap');
			$configuration = $bootstrap->getResource('ApplicationConfiguration');
			$applicationUploadPath = APPLICATION_PATH.'/../public'.$configuration->upload->path.'/readinglist';
			
			//Zend_Debug::dump($applicationUploadPath, '$applicationUploadPath');
			
			if (!$directoryHelper->directoryexists($applicationUploadPath)) {
			
				$directoryHelper->createdirectory($applicationUploadPath);
			
			}
			
			$cleanedWebsitePath = $fileHelper->cleanFilename($domain);
			
			$articleUploadPath = $applicationUploadPath.'/'.$cleanedWebsitePath;
			
			//Zend_Debug::dump($articleUploadPath, '$articleUploadPath');
			
			if (!$directoryHelper->directoryexists($articleUploadPath)) {
			
				$directoryHelper->createdirectory($articleUploadPath);
			
			}
			
			$fileExtension = $fileHelper->getExtensionByName($fileUrl);

			$fileName = $type.'_'.time().rand().$fileExtension;
			
			$filePath = $articleUploadPath.'/'.$fileName;
			
			$filePointer = fopen($filePath, 'w');
			fwrite($filePointer, $fileContent);
			fclose($filePointer);
			
			if ($type === 'thumbnail') {
			
				$imageHelper->thumbs($filePath, 200);
			
			}
			
			return $configuration->upload->path.'/readinglist/'.$cleanedWebsitePath.'/'.$fileName;
			
		} else {
		
			return false;
		
		}
	
	}

}