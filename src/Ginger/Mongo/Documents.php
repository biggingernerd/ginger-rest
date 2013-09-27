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
	
	private $_valid = false;
	private $_validationErrors = array();
	
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
		
		$find = $this->_find;
		
		$find = $this->_fixFind($find);
		
		$cursor = $this->_mongo->find($find);
		$this->total = $cursor->count();
		$this->limit = $this->_mongo->getLimit();
		$this->offset = $this->_mongo->getOffset();
		$this->sort = $this->_mongo->getSort();
		$this->direction = $this->_mongo->getDirection();
		$this->filters = $this->_find;
		$className = $this->_typeName;
		foreach($cursor as $document)
		{
			$this->items[] = new $className($document);
		}
	}
	
	private function _fixFind($find)
	{
		if(isset($find['id']))
		{
			try {
				$find['_id'] = new \MongoId($find['id']);	
			} catch(\MongoException $e) {
				$find['_id'] = $find['id'];
			}
			unset($find['id']);
		}
		
		return $find;
	}

	public function update($data)
	{
		$find = $this->_fixFind($this->_find);
		return $this->_mongo->update($find, $data);
	}
	
	public function delete()
	{
		return $this->_collection->remove($this->_find);
	}
	
	public function validate($data, $required = true)
	{
		$className = $this->_validateClass;
		$rules = $className::$rules;
		
		if(!$required)
		{
			unset($rules['required']);
		}
		
		$v = new \Valitron\Validator($data);
		$v->rules($rules);

		if($v->validate()) {
			$this->_valid = true;
		} else {
			$this->_validationErrors = $v->errors();
			$this->_valid = false;
		}
		return $this->_valid;
	}
	
	public function getErrors()
	{
		return $this->_validationErrors;
	}
}