<?php
/**
 * Ginger/Mongo/Document.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */
 
namespace Ginger\Mongo;

/**
 * Ginger Mongo Document Handler
 * 
 * @package Ginger\Library
 */
class Document 
{
	/**
	 * _className
	 * 
	 * @var mixed
	 * @access private
	 */
	private $_className;
	/**
	 * _mongoId
	 * 
	 * @var mixed
	 * @access private
	 */
	private $_mongoId;
	/**
	 * _mongo
	 * 
	 * @var mixed
	 * @access private
	 */
	private $_mongo;
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
	 * @var mixed
	 * @access private
	 */
	private $_valid;
	/**
	 * _validationErrors
	 * 
	 * @var mixed
	 * @access private
	 */
	private $_validationErrors;
	/**
	 * _found
	 * 
	 * (default value: false)
	 * 
	 * @var bool
	 * @access private
	 */
	private $_found = false;
	/**
	 * __construct function.
	 * 
	 * @access public
	 * @param bool $data (default: false)
	 * @return void
	 */
	public function __construct($data = false)
	{
		$this->_className = get_class($this);
		$this->_mongo = new \Ginger\Mongo($this->_databaseName, $this->_collectionName);
		$this->_collection = $this->_mongo->getCollection();
		$this->_prepare($data);
	}

	/**
	 * _prepare function.
	 * 
	 * @access private
	 * @param mixed $data
	 * @return void
	 */
	private function _prepare($data)
	{
		if($data && is_array($data))
		{
			if(isset($data['id']))
			{
				$this->_prepareId($data['id']);
				unset($data['id']);
			} elseif(isset($data['_id']))
			{
				$this->_prepareId($data['_id']);
				unset($data['_id']);
			}
			
			if(!$this->_mongoId)
			{
				$this->_mongoId = new \MongoId();
				$this->id = (string)$this->_mongoId;
			}
			
			// Prefill this document
			$this->_mapDocument($data);
		} elseif($data) {
			// Get document based on ID
			$id = $this->_prepareId($data);
			$data = $this->_get($id);
			
			$this->_mapDocument($data);
		} else {
			$this->_mongoId = new \MongoId();
			$this->id = (string)$this->_mongoId;
		}
	}
	
	/**
	 * _prepareId function.
	 * 
	 * @access private
	 * @param mixed $id
	 * @return void
	 */
	private function _prepareId($id)
	{
		if(is_string($id))
		{
			$this->id = $id;
			try {
				$this->_mongoId = new \MongoId($id);
			} catch(\MongoException $e) {
				$this->_mongoId = (string)$id;
			}
		} else {
			$this->id = (string)$id;
			$this->_mongoId = $id;
		}
		
		return $this->_mongoId;
	}

	/**
	 * _mapDocument function.
	 * 
	 * @access private
	 * @param mixed $data
	 * @return void
	 */
	private function _mapDocument($data)
	{
		$fields = get_public_object_vars($this);
		foreach($fields as $key => $value)
		{
			if(isset($data[$key]))
			{
				$this->{$key} = $data[$key];
			}
		}
	}
	
	/**
	 * _get function.
	 * 
	 * @access private
	 * @param mixed $id
	 * @return void
	 */
	private function _get($id)
	{
		$document = $this->_mongo->findOne(array("_id" => $id));
		if($document)
		{
			$this->_found = true;
		}
		return $document;
	}
	
	/**
	 * isFound function.
	 * 
	 * @access public
	 * @return void
	 */
	public function isFound()
	{
		return $this->_found;
	}
	
	/**
	 * save function.
	 * 
	 * @access public
	 * @return void
	 */
	public function save()
	{
		if(method_exists($this, "preSave"))
		{
			$this->preSave();
		}
	
		$this->_id = $this->_mongoId;
		unset($this->id);
		$a = $this->_mongo->upsert(array("_id" => $this->_mongoId), $this);	
		unset($this->_id);
		$this->id = (string)$this->_mongoId;
		
	}
	
	/**
	 * delete function.
	 * 
	 * @access public
	 * @return void
	 */
	public function delete()
	{
		$this->_mongo->remove(array("_id" => $this->_mongoId));
	}

	/**
	 * validate function.
	 * 
	 * @access public
	 * @return void
	 */
	public function validate()
	{
		$className = $this->_className."\\Validator";
		$rules = $className::$rules;
		
		$v = new \Valitron\Validator($this);
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