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

                    try {
                        
                        $mongoId = new MongoId($id);
                        
                    } catch (Exception $exception) {

                        return false;
                        
                    }

                    $previousData = $this->getById($id);
                    
                    $preparedData = $this->prepareData($data, $previousData);

                    // * we are using $set here because without $set the existing
                    // document would get replaced by the new, but we want to
                    // some fields like for example the creation date

                    // * as we use $set fields that have been emptied by the user
                    // will remain in the document and have an empty value, to
                    // remove those fields completly we have to unset them

                    if (count($preparedData) > 0) {

                        $this->userCollection->update(array('_id' => $mongoId), $preparedData, array('safe' => true));

                    }

				} else {
                    
                    $data = $this->removeEmptyFields($data);
		
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