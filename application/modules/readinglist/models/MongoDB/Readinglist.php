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
        //exit;
		
		if (!is_null($this->readinglistCollection)) {

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

                        // we are using $set here because without $set the existing
                        // document would get replaced by the new, but we want to
                        // some fields like for example the creation date
                        if (count($preparedData['set']) > 0) {
                            
                            $this->readinglistCollection->update(array('_id' => $mongoId), array('$set' => $preparedData['set']), array('safe' => true));
                            
                        }

                        // as we use $set fields that have been emptied by the user
                        // will remain in the document and have an empty value, to
                        // remove those fields completly we have to unset them
                        if (count($preparedData['unset']) > 0) {
                            
                            $this->readinglistCollection->update(array('_id' => $mongoId), array('$unset' => $preparedData['unset']), array('safe' => true));
                            
                        }

					} else {
                        
                        $data = $this->removeEmptyFields($data);

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