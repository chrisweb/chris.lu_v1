<?php
 
class Homepage_Model_DbTable_Testmodel extends Zend_Db_Table {
	
    protected $_name = 'test';  // table name
    protected $_primary = 'id'; // table primary key
    protected $_sequence = true; // if auto increment is on

    public function test() {
    	
		print_r('hello stef');
    	
    }
    
}