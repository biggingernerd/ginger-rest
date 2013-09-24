<?php

namespace Ginger\Mongo;

class Documents {
	
	public $total = 0;
	public $limit;
	public $offset;
	public $sort;
	public $direction;
	public $filters = array();
	public $items = array();
	
	private $_mongo;
	private $_find = array();
	private $_collection;
	
	protected $_databaseName;
	protected $_collectionName;
	protected $_typeName;	
	
	
	public function __construct($find = array())
	{
		$this->_find = $find;
		
		$this->_mongo = new \Ginger\Mongo($this->_databaseName, $this->_collectionName);
		$this->_collection = $this->_mongo->getCollection();
		
		$this->get();
	}
	
	public function get($find = false)
	{
		if($find)
		{
			$this->_find = $find;
		}
	
		$cursor = $this->_collection->find($this->_find);
		$this->total = $cursor->count();
		$this->limit = $this->_mongo->getLimit();
		$this->offset = $this->_mongo->getOffset();
		$this->sort = $this->_mongo->getSort();
		$this->direction = $this->_mongo->getDirection();
		$this->filter = $this->_find;
		$className = $this->_typeName;
		foreach($cursor as $document)
		{
			$this->items[] = new $className($document);
		}
	}
	
	public function update($data)
	{
		return $this->_collection->update($this->_find, $data);
	}
	
	public function delete()
	{
		return $this->_collection->remove($this->_find);
	}
	
}