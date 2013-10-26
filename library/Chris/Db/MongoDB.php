<?php
 
class Chris_Db_MongoDB extends Mongo
{
	
	protected function removeEmptyFields($data)
	{
	
		$cleanedData = array();
	
		foreach($data as $key => $value) {
		
			if (!empty($value)) {
                
                $cleanedData[$key] = $value;
                
            }
		
		}
	
		return $cleanedData;
	
	}
    
	protected function prepareData($data, $previousData)
	{
	
		$updateFields = array();
        $deleteFields = array();
        
        //Zend_Debug::dump($data);
        //Zend_Debug::dump($previousData);
        //exit;
	
        // using $set you just update fields that have a new value, other fields
        // like the creation_date don't get modified
		foreach($data as $key => $value) {
		
			if (!empty($value)) {
                
                $updateFields[$key] = $value;
                
            } else {

                if (!empty($previousData[$key])) {
                
                    $deleteFields[$key] = $previousData[$key];
                    
                }
                
            }
		
		}
        
        $preparedData = array();

        if (count($updateFields) > 0) {

            $preparedData['$set'] = $updateFields;

        }

        if (count($deleteFields) > 0) {

            $preparedData['$unset'] = $deleteFields;

        }

        //Zend_Debug::dump($preparedData);exit;
	
		return $preparedData;
	
	}

} 