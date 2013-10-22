<?php
/**
 * Ginger/Mongo/Documents.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */

namespace Ginger\Mongo;

/**
 * Documents class.
 */
class Documents {
	
	/**
	 * total
	 * 
	 * (default value: 0)
	 * 
	 * @var int
	 * @access public
	 */
	public $total = 0;
	/**
	 * limit
	 * 
	 * @var mixed
	 * @access public
	 */
	public $limit;
	/**
	 * offset
	 * 
	 * @var mixed
	 * @access public
	 */
	public $offset;
	/**
	 * sort
	 * 
	 * @var mixed
	 * @access public
	 */
	public $sort;
	/**
	 * direction
	 * 
	 * @var mixed
	 * @access public
	 */
	public $direction;
	/**
	 * filters
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access public
	 */
	public $filters = array();
	/**
	 * items
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access public
	 */
	public $items = array();
	
	/**
	 * _mongo
	 * 
	 * @var mixed
	 * @access private
	 */
	private $_mongo;
	/**
	 * _find
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access private
	 */
	private $_find = array();
	/**
	 * _collection
	 * 
	 * @var mixed
	 * @access private
	 */
	private $_collection;
	
	/**
	 * _valid
	 * 
	 * (default value: false)
	 * 
	 * @var bool
	 * @access private
	 */
	private $_valid = false;
	/**
	 * _validationErrors
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access private
	 */
	private $_validationErrors = array();
	
	/**
	 * _databaseName
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $_databaseName;
	/**
	 * _collectionName
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $_collectionName;
	/**
	 * _typeName
	 * 
	 * @var mixed
	 * @access protected
	 */
	protected $_typeName;	
	
	private $plainId = false;
	
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @param array $find (default: array())
	 * @return void
	 */
	public function __construct($find = array(), $plainId = false)
	{
		$this->_find = $find;
		
		$this->plainId = (string)$plainId;

		$this->_mongo = new \Ginger\Mongo($this->_databaseName, $this->_collectionName);
		$this->_collection = $this->_mongo->getCollection();
		
		$this->get();
	}
	
	/**
	 * get function.
	 * 
	 * @access public
	 * @param bool $find (default: false)
	 * @return void
	 */
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
	
	/**
	 * _fixFind function.
	 * 
	 * @access private
	 * @param mixed $find
	 * @return void
	 */
	private function _fixFind($find)
	{
		if(isset($find['id']) && !$this->plainId) {
			try {
				$find['_id'] = new \MongoId($find['id']);	
			} catch(\MongoException $e) {
				$find['_id'] = $find['id'];
			}
			unset($find['id']);
		} elseif(isset($find['id'])) {
    		$find['_id'] = (string)$find['id'];
    		unset($find['id']);
		}
		
		return $find;
	}

	/**
	 * update function.
	 * 
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	public function update($data)
	{
		$find = $this->_fixFind($this->_find);
		return $this->_mongo->update($find, $data);
	}
	
	/**
	 * delete function.
	 * 
	 * @access public
	 * @return void
	 */
	public function delete()
	{
	    $find = $this->_fixFind($this->_find);
		return $this->_collection->remove($this->_find);
	}
	
	/**
	 * validate function.
	 * 
	 * @access public
	 * @param mixed $data
	 * @param bool $required (default: true)
	 * @return void
	 */
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
	
	/**
	 * getErrors function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getErrors()
	{
		return $this->_validationErrors;
	}
}