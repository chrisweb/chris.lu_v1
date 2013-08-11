<?php

class User_AdminController extends Zend_Controller_Action {

    public function indexAction() {

		$userModel = new User_Model_MongoDB_User();
		
		$where = array();
		$keys = array('username');
		
		$cursor = $userModel->getList($where, $keys);
		
		$sort = array('username' => 1);
		
		// paginator
		$cacheIdentifier = md5('article_list_title_header');
        $adapter = new Chris_Paginator_Adapter_MongoDB($cursor, $cacheIdentifier, $sort);
        $paginator = new Zend_Paginator($adapter);

        $page = (int) $this->getRequest()->getParam('page', 1);

        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(10);
        $paginator->setPageRange(5);
		
		$this->view->paginator = $paginator;
		
		$this->view->routeName = 'useradminindex';
	
    }
	
    public function manageAction() {

		// filter user id
		$id = $this->getRequest()->getParam('id', 0);
		$alnumFilter = new Zend_Filter_Alnum();
		$filteredId = $alnumFilter->filter($id);
		
		$userModel = new User_Model_MongoDB_User();
		
        $options = null;
		
		$mode = ($id) ? 'edit' : 'new';
		$roleOptions = array('admin' => 'ADMIN');
		
		$manageUserForm = new User_Form_ManageUser($options, $mode, $roleOptions);

        if ($this->getRequest()->isPost()) {

            // get unfiltered POST data
            $formData = $this->getRequest()->getPost();
			
			//Zend_Debug::dump($formData);
			//exit;

            if ($manageUserForm->isValid($formData)) {

                // get form POST values, now with applied filters
                $formData = $manageUserForm->getValues();

				if ($id) $formData['_id'] = $id;
				
                //Zend_Debug::dump($formData);
                //exit;
				
				$userConfiguration = Zend_Registry::get('UserConfiguration');
				
				$salt = $userConfiguration->authentification->password->salt;
				
				if (empty($formData['password'])) {
				
					unset($formData['password']);
					
				} else{
				
					$formData['password'] = md5($formData['password'].$salt);
					
				}

                $response = $userModel->saveData($formData);

                if ($response) {
				
					$this->_redirect('/user/admin');
					
				} else {
				
					Zend_Debug::dump($response);
				
				}

            } else {

                $manageUserForm->populate($formData);
                $this->view->manageUserForm = $manageUserForm;

            }

        } else {

			if ($id) {
			
				$keys = array('password' => 0);
			
				$userData = $userModel->getById($id, $keys);
				
				//Zend_Debug::dump($userData);
			
				$manageUserForm->populate($userData);
			
			}
		
            $this->view->manageUserForm = $manageUserForm;

        }
	
    }
	
	public function deleteAction() {
	
		$id = $this->getRequest()->getParam('id', 0);
		
		$alnumFilter = new Zend_Filter_Alnum();
		
		$filteredId = $alnumFilter->filter($id);
		
		$userModel = new User_Model_MongoDB_User();
		
		$keys = array('username');
		
		$user = $userModel->getById($id, $keys);
		
		$this->view->user = $user;
		
		//Zend_Debug::dump($user);
		
		$options = array();
		
		$deleteUserForm = new User_Form_DeleteUser($options);

        if ($this->getRequest()->isPost()) {

            // get unfiltered POST data
            $formData = $this->getRequest()->getPost();

            if ($deleteUserForm->isValid($formData)) {
			
				//Zend_Debug::dump($formData);
				//exit;
				
				if (array_key_exists('yes', $formData)) {
				
					$userModel->deleteEntry($id);
					
				}
				
				$this->_redirect('/user/admin');
			
			}
			
		} else {
		
			$this->view->deleteUserForm = $deleteUserForm;
		
		}
	
	}

}