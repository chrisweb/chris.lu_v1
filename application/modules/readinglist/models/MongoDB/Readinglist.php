<?php
 
class Readinglist_Model_MongoDB_Readinglist extends Chris_Db_MongoDB
{

	protected $readinglistCollection;

	public function __construct()
	{

		$frontController = Zend_Controller_Front::getInstance();

		$bootstrap = $frontController->getParam('bootstrap');
		
		$mongoConnection = false;
		
		if ($bootstrap->hasResource('DatabaseConnection')) {
		
			$mongoConnection = $bootstrap->getResource('DatabaseConnection');
			
		}
		
		if ($mongoConnection) {
		
			$this->readinglistCollection = $mongoConnection->selectCollection('readinglist');
			
		} else {
		
			$this->readinglistCollection = null;
		
		}
	
	}

	public function getById($id, array $keys = array())
	{
	
		$data = array();
	
		if (!is_null($this->readinglistCollection)) {
	
			$data = $this->readinglistCollection->findOne(array('_id' => new MongoId($id)), $keys);
			
		}
		
		return $data;
	
	}
	
	public function getList(array $where = array(), array $keys = array())
	{
	
		$cursor = null;
	
		if (!is_null($this->readinglistCollection)) {
	
			$cursor = $this->readinglistCollection->find($where, $keys);
			
			//Zend_Debug::dump($cursor->getNext());
			
		}
		
		return $cursor;		
	
	}
	
	public function saveData(array $data = array())
	{
	
		//Zend_Debug::dump($data);
		
		if (!is_null($this->readinglistCollection)) {
			
			$data = $this->removeEmptyEntries($data);
			
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
						
						// we dont use set, because if user deletes content of field in form, we also want to remove the key/value pair completly from db, we don't keep keys that have an empty value in the db
						// we could use set and pass empty values for the fields we want to be empty, but this would also keep the key, we prefer to delete it all (key and value) if there is no data in the field
						$this->readinglistCollection->update(array('_id' => new MongoId($id)), array('$set' => $data), array('safe' => true));

					} else {
			
						$data['last_update_date'] = new MongoDate();
						$data['publish_date'] = new MongoDate();
			
						$id = $this->readinglistCollection->insert($data, array('safe' => true));
						
					}
					
					return $id;
					
				} catch (MongoCursorExcveption $e) {
				
					return null;
				
				}
				
			} else {
			
				return null;
			
			}
			
		} else {
		
			return null;
		
		}
	
	}

	public function deleteEntry($id)
	{
	
		if (!is_null($this->readinglistCollection)) {
	
			$this->readinglistCollection->remove(array('_id' => new MongoId($id)));
			
			return true;
			
		} else {
		
			return false;
		
		}
	
	}

}