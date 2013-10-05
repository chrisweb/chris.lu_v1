<?php
 
class Article_Model_MongoDB_Comment
{

	protected $articleCollection;

    /**
     * 
     * @throws Exception
     */
	public function __construct()
	{

		$frontController = Zend_Controller_Front::getInstance();

		$bootstrap = $frontController->getParam('bootstrap');
		
		$mongoConnection = false;
		
		if ($bootstrap->hasResource('DatabaseConnection')) {
		
			$mongoConnection = $bootstrap->getResource('DatabaseConnection');
			
		}
		
		if ($mongoConnection) {
		
			$this->commentCollection = $mongoConnection->selectCollection('comment');
			
		} else {
		
			throw new Exception('loading collection failed');
		
		}
	
	}

    /**
     * 
     * @param type $id
     * @param array $keys
     * @return type
     */
	public function getById($id, array $keys = array())
	{
	
		$data = $this->commentCollection->findOne(array('_id' => new MongoId($id)), $keys);
		
		return $data;
	
	}
	
    /**
     * 
     * @param array $where
     * @param array $keys
     * @return type
     */
	public function getList(array $where = array(), array $keys = array())
	{
	
		$cursor = $this->commentCollection->find($where, $keys);
		
		//Zend_Debug::dump($cursor->getNext());
		
		return $cursor;		
	
	}
	
    /**
     * 
     * @param array $data
     * @return boolean
     */
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
					
					$this->commentCollection->update(array('_id' => new MongoId($id)), array('$set' => $data), array('safe' => true));

				} else {
		
					$data['last_update_date'] = new MongoDate();
					$data['publish_date'] = new MongoDate();
		
					$id = $this->commentCollection->insert($data, array('safe' => true));
					
				}
				
				return $id;
				
			} catch (MongoCursorExcveption $e) {
			
				return false;
			
			}
			
		} else {
		
			return false;
		
		}
	
	}

    /**
     * 
     * @param type $id
     */
	public function deleteEntry($id)
	{
	
		$this->commentCollection->remove(array('_id' => new MongoId($id)));
	
	}
    
}