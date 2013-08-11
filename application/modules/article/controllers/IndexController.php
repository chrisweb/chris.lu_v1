<?php

class Article_IndexController extends Zend_Controller_Action
{

    public function init()
	{

		$chrisContext = $this->_helper->getHelper('ChrisContext');

		$chrisContext	->addActionContext('read', 'jquerymobile')
						->addActionContext('tag', 'jquerymobile')
						->initContext('jquerymobile');
		
		parent::init();
		
	}

    public function readAction()
	{
	
		$id = $this->getRequest()->getParam('id', 0);
		
		$alnumFilter = new Zend_Filter_Alnum();
		
		$filteredId = $alnumFilter->filter($id);

		$articleModel = new Article_Model_MongoDB_Article();
		
		$article = $articleModel->getById($filteredId);
		
		//Zend_Debug::dump($article);
		
		// detch related articles list based on related tag
		if (array_key_exists('relatedTag', $article)) {
		
			$where = array('relatedTag' => $article['relatedTag']);
			$keys = array('_id', 'title');
		
			$relatedArticles = $articleModel->getList($where, $keys);
			
			$article['relatedArticles'] = $relatedArticles;
			
			unset($article['relatedTag']);
		
		}
		
		$this->view->article = $article;

    }
	
    public function rssAction()
	{

		$this->_helper->layout()->disableLayout(); 
		$this->_helper->viewRenderer->setNoRender();
	
		$bootstrap = $this->getInvokeArg('bootstrap');
        $configuration = $bootstrap->getResource('applicationConfiguration');
		
		//Zend_Debug::dump($configuration);
		//exit;
		
        $websiteDomain = $configuration->website->domain;
        $websiteTitle = $configuration->website->title;
		
        $language = 'en';
		
        $articleModel = new Article_Model_MongoDB_Article();
		
		$where = array('status' => '2');
		$keys = array('_id', 'title', 'header', 'publish_date');
		
		$cursor = $articleModel->getList($where, $keys);
		
		$cursor->sort(array('publish_date' => -1));
		$cursor->limit(20);
		
		$feedsArray = array();
		
		$first = true;

        foreach ($cursor as $article) {
		
			//Zend_Debug::dump($article);
			//exit;

			$feedDate = $article['publish_date']->sec;
			
			if ($first) {
				$first = false;
				$pubDate = $feedDate;
			}

            $feedsArray[] = array(
                    'title' 		=> $article['title'],
                    'link' 			=> $websiteDomain.'/article/read/'.$article['_id'],
                    'guid' 			=> $websiteDomain.'/article/read/'.$article['_id'],
                    'description' 	=> $article['header'],
                    'lastUpdate' 	=> $feedDate
            );

        }
		
		//Zend_Debug::dump($feedsArray);
		//Zend_Debug::dump($websiteTitle);
		//exit;
		
		if (count($feedsArray) > 0) {
		
			$feedArray = array(
				'title' 		=> $websiteTitle,
				'link' 			=> $websiteDomain.'/article/rss',
				'description' 	=> $websiteTitle,
				'language' 		=> $language.'-'.$language,
				'charset' 		=> 'utf-8',
				'pubDate' 		=> $pubDate,
				'generator' 	=> $websiteTitle.' Online Feed Generator',
				'entries' 		=> $feedsArray
			);

			$feedOutput = Zend_Feed::importArray($feedArray, 'rss');
			$feedOutput->send();
			
		}

    }
	
	public function tagAction()
	{
	
		$id = $this->getRequest()->getParam('id', 0);
		
		$this->view->tagId = $id;
		
		//Zend_Debug::dump($id);
		
		$alnumFilter = new Zend_Filter_Alnum();
		
		$filteredId = $alnumFilter->filter($id);

		$articleModel = new Article_Model_MongoDB_Article();
		
		$where = array('status' => '2', 'tags.id' => $id);
		$keys = array('_id', 'title', 'header', 'tags', 'image', 'image_alt');
		
		$cursor = $articleModel->getList($where, $keys);
		
		//Zend_Debug::dump($cursor->getNext());
		//exit;
		
		$sort = array('publish_date' => -1);
		
		// paginator
		$cacheIdentifier = md5('article_list_title_header');
        $adapter = new Chris_Paginator_Adapter_MongoDB($cursor, $cacheIdentifier, $sort);
        $paginator = new Zend_Paginator($adapter);

        $page = (int) $this->getRequest()->getParam('page', 1);

        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
		
		//Zend_Debug::dump($paginator);
		
		$this->view->paginator = $paginator;
		
		$this->view->routeName = 'articleindextag';
	
	}

}