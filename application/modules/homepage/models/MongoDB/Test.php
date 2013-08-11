<?php

class Homepage_Model_MongoDB_Test
{

	protected $homepageCollection;

	public function __construct()
	{

		$frontController = Zend_Controller_Front::getInstance();

		$bootstrap = $frontController->getParam('bootstrap');
		
		$mongoConnection = false;
		
		if ($bootstrap->hasResource('DatabaseConnection')) {
		
			$mongoConnection = $bootstrap->getResource('DatabaseConnection');
			
		}
		
		if ($mongoConnection) {
		
			$this->homepageCollection = $mongoConnection->selectCollection('homepage');
			
		} else {
		
			throw new Exception('loading collection failed');
		
		}
	
	}

	public function insertData($data)
	{
	
		$data['last_update_date'] = new MongoDate();
		$data['publish_date'] = new MongoDate();

		$id = $this->homepageCollection->insert($data, array('safe' => true));
		
		Zend_Debug::dump($id, 'insert data id');
		
		if (isset($id)) return $id;	
	
	}
	
	public function updateData($id, array $data, array $options = array('safe' => true), $overwrite = false)
	{

		$data['last_update_date'] = new MongoDate();
		
		if ($overwrite) {
		
			// without $set, clear all fields (key and value), then readd fields that got passed
			$input = $data;
			
		} else {
		
			// with $set, keep db entry as is, only replace fields that get passed
			$input = array('$set' => $data);
		
		}
		
		Zend_Debug::dump($input, '$input');
		
		$id = $this->homepageCollection->update(array('_id' => new MongoId($id)), $input, $options);
		
		Zend_Debug::dump($id, 'update data id');
		
		if (isset($id)) return $id;	
	
	}
	
	public function getData($id, array $keys = array())
	{
	
		$data = $this->homepageCollection->findOne(array('_id' => new MongoId($id)), $keys);
		
		Zend_Debug::dump($data, 'get $data');
		
		return $data;
	
	}
	
	public function getList(array $where = array(), array $keys = array())
	{
	
		$cursor = $this->homepageCollection->find($where, $keys);
		
		//Zend_Debug::dump($cursor->getNext());
		
		while($cursor->hasNext()) {
		
			Zend_Debug::dump($cursor->getNext(), 'get list entry');
		
		}
		
		return $cursor;		
	
	}
	
	public function deleteData($id)
	{
	
		$id = $this->homepageCollection->remove(array('_id' => new MongoId($id)));
		
		Zend_Debug::dump($id, 'delete data id');
		
		if (isset($id)) return $id;	
	
	}

}