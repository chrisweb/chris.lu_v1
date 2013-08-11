<?php
 
class User_Model_MongoDB_User extends Chris_Db_MongoDB
{

	protected $userCollection;

	public function __construct()
	{

		$frontController = Zend_Controller_Front::getInstance();

		$bootstrap = $frontController->getParam('bootstrap');
		
		$mongoConnection = false;
		
		if ($bootstrap->hasResource('DatabaseConnection')) {
		
			$mongoConnection = $bootstrap->getResource('DatabaseConnection');
			
		}
		
		if ($mongoConnection) {
		
			$this->userCollection = $mongoConnection->selectCollection('user');
			
		} else {
		
			throw new Exception('loading collection failed');
		
		}
	
	}

	public function getById($id, array $keys = array())
	{
	
		$data = $this->userCollection->findOne(array('_id' => new MongoId($id)), $keys);
		
		return $data;
	
	}
	
	public function getList(array $where = array(), array $keys = array())
	{
	
		$cursor = $this->userCollection->find($where, $keys);
		
		//Zend_Debug::dump($cursor->getNext());
		
		return $cursor;		
	
	}
	
	public function saveData(array $data = array())
	{
	
		//Zend_Debug::dump($data);
		//exit;
		
		if (count($data) > 0) {
		
			try {
		
				if (array_key_exists('_id', $data)) {

					$id = $data['_id'];

					unset($data['_id']);

					$data['last_update_date'] = new MongoDate();
					
					// if you use $set only fields that get passed in array will be updated other fields will be unchanged
					// if you dont use $set, mongodb will delete existing entry and replace it completly with new data
					
					// we use set, because if user does not change his password we dont want the db password field to get erased
					$updateData = array('$set' => $data);

					$this->userCollection->update(array('_id' => new MongoId($id)), $updateData, array('safe' => true));

				} else {
		
					$data['last_update_date'] = new MongoDate();
					$data['publish_date'] = new MongoDate();
		
					$id = $this->userCollection->insert($data, array('safe' => true));
					
				}
				
				return $id;
				
			} catch (MongoCursorExcveption $e) {
			
				return false;
			
			}
			
		} else {
		
			return false;
		
		}
	
	}

	public function deleteEntry($id)
	{
	
		$this->userCollection->remove(array('_id' => new MongoId($id)));
	
	}

}