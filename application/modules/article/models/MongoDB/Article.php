<?php
 
class Article_Model_MongoDB_Article
{

	protected $articleCollection;

	public function __construct()
	{

		$frontController = Zend_Controller_Front::getInstance();

		$bootstrap = $frontController->getParam('bootstrap');
		
		$mongoConnection = false;
		
		if ($bootstrap->hasResource('DatabaseConnection')) {
		
			$mongoConnection = $bootstrap->getResource('DatabaseConnection');
			
		}
		
		if ($mongoConnection) {
		
			$this->articleCollection = $mongoConnection->selectCollection('article');
			
		} else {
		
			throw new Exception('loading collection failed');
		
		}
	
	}

	public function getById($id, array $keys = array())
	{
	
        try {
            
            $mongoId = new MongoId($id);
            
            $data = $this->articleCollection->findOne(array('_id' => $mongoId), $keys);
            
            return $data;
            
        } catch (MongoException $exception) {
            
            return null;
            
        }
	
	}
	
	public function getList(array $where = array(), array $keys = array())
	{
	
		$cursor = $this->articleCollection->find($where, $keys);
		
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
					
					$this->articleCollection->update(array('_id' => $mongoId), array('$set' => $data), array('safe' => true));

				} else {
		
					$data['last_update_date'] = new MongoDate();
					$data['publish_date'] = new MongoDate();
		
					$id = $this->articleCollection->insert($data, array('safe' => true));
					
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
	
        try {
            
            $mongoId = new MongoId($id);
            
            $this->articleCollection->remove(array('_id' => $mongoId));
            
        } catch (Exception $exception) {

            return false;
            
        }
        
		return true;
	
	}

}