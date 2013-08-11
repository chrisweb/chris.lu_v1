<?php

/**
 * @see Zend_Paginator_Adapter_Interface
 */
require_once 'Zend/Paginator/Adapter/Interface.php';

/**
TODO: instead of passing cursor to construct pass query parameters
TODO: add caching functionality
**/

class Chris_Paginator_Adapter_MongoDB implements Zend_Paginator_Adapter_Interface
{

    protected $_cursor = null;
	
    protected $_collectionCount = null;
	
    protected $_cacheIdentifier = null;
	
	protected $_sort = null;

    public function __construct(MongoCursor $cursor, $cacheIdentifier, $sort = null)
    {
	
        $this->_cursor = $cursor;
		
		$this->_cacheIdentifier = $cacheIdentifier;
		
		$this->_sort = $sort;
		
    }
	
    public function getCacheIdentifier()
    {
	
        return $this->_cacheIdentifier;
		
    }

	public function getItems($offset, $itemCountPerPage) 
	{
	
		if (!is_null($this->_sort)) $this->_cursor->sort($this->_sort);
	
		$cursor = $this->_cursor->skip($offset)->limit($itemCountPerPage);
		
		return $cursor;
		
	}

    public function count()
    {
	
        if ($this->_collectionCount === null) {
            $this->setCollectionCount($this->_cursor->count());
        }

        return $this->_collectionCount;
		
    }
	
	public function setCollectionCount($collectionCount)
	{
	
		$this->_collectionCount = $collectionCount;
	
	}
	
}