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
	private $_className;
	private $_mongoId;
	private $_mongo;
	private $_collection;
	private $_valid;
	private $_validationErrors;

	public function __construct($data = false)
	{
		$this->_className = get_class($this);
		$this->_mongo = new \Ginger\Mongo($this->_databaseName, $this->_collectionName);
		$this->_collection = $this->_mongo->getCollection();
		$this->_prepare($data);
	}

	private function _prepare($data)
	{
		if($data && is_array($data))
		{
			if(isset($data['id']))
			{
				$this->_prepareId($data['id']);
				unset($data['id']);
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
	
	private function _get($id)
	{
		$document = $this->_mongo->findOne(array("_id" => $id));
		return $document;
	}
	
	public function save()
	{
		$this->_id = $this->_mongoId;
		$a = $this->_mongo->upsert(array("_id" => $this->_mongoId), $this);	
		unset($this->_id);
		
	}
	
	public function delete()
	{
		$this->_mongo->remove(array("_id" => $this->_mongoId));
	}

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
	
	public function getErrors()
	{
		return $this->_validationErrors;
	}
}