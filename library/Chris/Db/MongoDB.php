<?php
 
class Chris_Db_MongoDB extends Mongo
{
	
	protected function removeEmptyEntries($data)
	{
	
		$cleanData = array();
	
		foreach($data as $key => $value) {
		
			if (!empty($value)) $cleanData[$key] = $value;
		
		}
	
		return $cleanData;
	
	}

} 