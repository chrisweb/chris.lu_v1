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
	
		$id = $this->getRequest()->getParam('id', '');
        
        if (!empty($id)) { 

            //Zend_Debug::dump($id, '$id: ');
            //exit;

            $alnumFilter = new Zend_Filter_Alnum();

            $filteredId = $alnumFilter->filter($id);

            $articleModel = new Article_Model_MongoDB_Article();

            $article = $articleModel->getById($filteredId);

            //Zend_Debug::dump($article, '$article: ');

            // fetch related articles list based on related tag
            if (array_key_exists('relatedTag', $article)) {

                $where = array('relatedTag' => $article['relatedTag']);
                $keys = array('_id', 'title');

                $relatedArticles = $articleModel->getList($where, $keys);

                $article['relatedArticles'] = $relatedArticles;

                unset($article['relatedTag']);

            }

            $this->view->article = $article;

            // get the comments for this article
            // TODO: comments pagination
            $commentModel = new Article_Model_MongoDB_Comment();

            $where = array('article_id' => $filteredId);
            $keys = array();

            $commentsCursor = $commentModel->getList($where, $keys);

            $sort = array('publish_date' => 1);

            $commentsCursor->sort($sort);

            $this->view->comments = $commentsCursor;

            // get the comment form
            $commentForm = new Article_Form_ManageComment();

            $this->view->commentForm = $commentForm;

            $commentForm->setAction($this->_helper->url->url(array('article_id' => $filteredId), 'articlecomment'));
            
        } else {
            
            // TODO: show 404
            
        }

    }
    
    public function commentAction()
	{
        
        $rawId = $this->getRequest()->getParam('id', '');
        $rawArticleId = $this->getRequest()->getParam('article_id', '');
        
        //Zend_Debug::dump($rawId, '$rawId: ');
        //Zend_Debug::dump($rawArticleId, '$rawArticleId: ');

        $alnumFilter = new Zend_Filter_Alnum();

        $filteredId = $alnumFilter->filter($rawId);

        $filteredArticleId = $alnumFilter->filter($rawArticleId);
        
        //Zend_Debug::dump($filteredId, '$filteredId: ');
        //Zend_Debug::dump($filteredArticleId, '$filteredArticleId: ');

        $auth = Zend_Auth::getInstance();
        
        $user = '';

        if ($auth->hasIdentity()) {

            $user = $auth->getIdentity();
            
            //Zend_Debug::dump((string) $user->_id, '$user->_id: ');

        }
        
        // get the comment model
        $commentModel = new Article_Model_MongoDB_Comment();
        
        // get the comment form
        $commentForm = new Article_Form_ManageComment();
        
        // add the action (submit url) to the form
        if (!empty($filteredId)) {
        
            $parameters = array('id' => $filteredId, 'article_id' => $filteredArticleId);
            
        } else {
            
            $parameters = array('id' => $filteredId);
            
        }
        
        $commentForm->setAction($this->_helper->url->url($parameters, 'articlecomment'));
        
        if ($this->getRequest()->isPost()) {

            // get unfiltered POST data
            $formData = $this->getRequest()->getPost();
			
			//Zend_Debug::dump($formData);
			//exit;

            if ($commentForm->isValid($formData)) {

                // get form POST values, now with applied filters
                $formData = $commentForm->getValues();
                
                unset($formData['captcha']);
                
                if (is_object($user)) {
                    
                    $formData['user_id'] = (string) $user->_id;
                    
                }
                
                $formData['article_id'] = $filteredArticleId;
                
                //Zend_Debug::dump($filteredId);
                
                if (!empty($filteredId)) {
                    
                    $formData['_id'] = $filteredId;
                    
                }
                
                //Zend_Debug::dump($formData);
                //exit;
                
                // save the comment in the database
                $commentModel->saveData($formData);
                
                // redirect to the article
                $this->_helper->redirector->setGotoRoute(array('id' => $filteredArticleId), 'articleindexread');
                
            } else {
            
                $commentForm->populate($formData);

                $this->view->commentForm = $commentForm;
                
            }

        } else {
            
            $acl = Zend_Registry::get('Acl');

            $comment = '';

            if ($filteredId !== 0) {

                $keys = array('name', 'email', 'comment');

                $comment = $commentModel->getById($filteredId, $keys);

            }
            
            //Zend_Debug::dump($comment, '$comment: ');
            //Zend_Debug::dump($user, '$user: ');

            // if the id is defined and the user is the owner load the comment
            // and populate the form, so that he can edit it
            if (is_array($comment) && is_object($user)) {
                
                $aclUser = new Chris_Acl_UserRole($user);
                $aclComment = new Chris_Acl_CommentResource($comment);
                
                if ($acl->isAllowed($aclUser, $aclComment, 'edit')) {

                    $commentModel = new Article_Model_MongoDB_Comment();

                    $comment = $commentModel->getById($filteredId);

                    //Zend_Debug::dump($comment, '$comment: ');

                    $commentForm->populate($comment);
                    
                }


            }

            $this->view->commentForm = $commentForm;
            
        }

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
		
		//Zend_Debug::dump($id);
		
		$alnumFilter = new Zend_Filter_Alnum();
		
		$filteredId = $alnumFilter->filter($id);
        
        $this->view->tagId = $filteredId;

		$articleModel = new Article_Model_MongoDB_Article();
		
		$where = array('status' => '2', 'tags.id' => $filteredId);
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