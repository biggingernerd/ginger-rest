<?php
/**
 * Ginger/Mongo.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */

namespace Ginger;

use \Ginger\Mongo\Defaults;


/**
 * Ginger Mongo Handler
 * 
 * @package Ginger\Library
 */
class Mongo 
{
	/**
	 * @var \MongoClient $client MongoClient
	 */
	private $client;
	
	/**
	 * @var int $limit Limit used in queries
	 */
	private $limit;
	/**
	 * @var int $offset Offset used in queries
	 */
	private $offset;
	
	/**
	 * @var string $sort Sort field used in queries
	 */
	private $sort;
	
	/**
	 * @var string $direction Sort direction used in queries
	 */
	private $direction;
	
	/**
	 * @var array $fields Fields returned from query
	 */
	private $fields;
	
	/**
	 * @var string $databaseName Database name used
	 */
	private $databaseName;
	
	/**
	 * @var string $collectionName Collection name used
	 */
	private $collectionName;
	/**
	 * @var \MongoCollection $collection Collection
	 */
	private $collection;
	
	/**
	 * Results found
	 * @var int $total Total results found
	 */
	private $total = 0;
	
	/**
	 * Set defaults and connect to the server
	 * 
	 * @param string $database
	 * @param string $collection
	 */
	public function __construct($database, $collection) 
	{
		$defaults = new Defaults();
		
		$this->setDatabaseName($database);
		$this->setCollectionName($collection);
		
		$this->setLimit($defaults->limit);
		$this->setOffset($defaults->offset);
		$this->setSort($defaults->sort);
		$this->setDirection($defaults->direction);
		
		$this->client = new \MongoClient();
		$this->setCollection();
	}
	
	
	/**
	 * Return a resultset cursor based on $find
	 * 
	 * @param array $find
	 * @return MongoCursor
	 */
	public function find($find = null) 
	{
		if(isset($find['id'])) {
			if(is_array($find['id'])) {
				$find['_id']['$in'] = array();
				foreach($find['id'] as $id) {
					$find['_id']['$in'][] = new \MongoId($id);
				}
			} else {
				$find['_id'] = new \MongoId($find['id']);
			}
			
			unset($find['id']);	
		}

		$cursor = $this->getCollection()->find($find)->limit($this->getLimit())->skip($this->getOffset());
		$cursor->sort(array($this->getSort() => $this->getMongoDirection()));
		
		$this->setTotal($cursor->count());
		
		return $cursor;
	}
	
	/**
	 * Find One result based on find
	 * @param array $find
	 * @return array
	 */
	public function findOne($find = array()) 
	{
		return $this->getCollection()->findOne($find);
	}
	/**
	 * Insert new document
	 * @param mixed $data
	 */
	public function insert($data) 
	{
		$this->getCollection()->insert($this->getSafeVars($data));
	}
	
	/**
	 * Update one or more documents based on $find
	 * @param array $find
	 * @param mixed $data
	 */
	public function update($find = array(), $data) 
	{
		return $this->getCollection()->update($find, array('$set' => $this->getSafeVars($data)), array("multiple" => true));	
	}
	
	/**
	 * Insert if $find isn't matched, update if it is.
	 * 
	 * @param array $find
	 * @param mixed $data
	 */
	public function upsert($find = array(), $data) 
	{
		return $this->getCollection()->update($find, $this->getSafeVars($data), array("upsert" => true));
	}
	
	/**
	 * Remove documents based on $find
	 * @param array $find
	 */
	public function remove($find)
	{
		if(count($find) > 0) {
			return $this->getCollection()->remove($find);
		} else {
			return array("n" => 0);
		}
	}
	
	/**
	 * Get only public vars
	 * @param mixed $data
	 * @return multitype:
	 */
	private function getSafeVars($data)
	{
		if(is_object($data)) {
			$data = get_object_vars($data);
		}
		
		return $data;
	}
	
	/**
	 * Set limit
	 * @param int $limit
	 */
	public function setLimit($limit) 
	{
		$this->limit = (int)$limit;
	}
	/**
	 * Get limit
	 * @return number
	 */
	public function getLimit() 
	{
		return $this->limit;
	}
	/**
	 * Set offset
	 * 
	 * @param number $offset
	 */
	public function setOffset($offset) 
	{
		$this->offset = (int)$offset;
	}
	/**
	 * Get offset
	 * @return number
	 */
	public function getOffset() 
	{
		return $this->offset;
	}
	/**
	 * Get sort
	 * @return string
	 */
	public function getSort() 
	{
		return $this->sort;
	}
	/**
	 * Set sort
	 * @param string $sort
	 */
	public function setSort($sort) 
	{
		$this->sort = $sort;
	}
	
	/**
	 * getMongoDirection function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getMongoDirection()
	{
		if($this->direction == "asc") {
			return 1;
		} else {
			return -1;
		}
	}
	
	/**
	 * Get direction
	 * @return string
	 */
	public function getDirection() 
	{
		return $this->direction;
	}
	/**
	 * Set direction
	 * @param string $direction
	 */
	public function setDirection($direction) 
	{
		$this->direction = $direction;
	}
	
	/**
	 * Set fields
	 * @param array $fields
	 */
	public function setFields($fields = array()) 
	{
		$this->fields = $fields;
	}
	
	/**
	 * Get Fields
	 * @return array
	 */
	public function getFields() 
	{
		return $this->fields;
	}

	/**
	 * Set total
	 * @param number $total
	 */
	public function setTotal($total) 
	{
		$this->total = (int)$total;
	}
	
	/**
	 * Get total
	 * 
	 * return number
	 */
	public function getTotal()
	{
		return $this->total;
	}
	
	/**
	 * Set database name
	 * @param string $databaseName
	 */
	public function setDatabaseName($databaseName)
	{
		$this->databaseName = $databaseName;
	}
	/**
	 * Set collection name
	 * @param string $collectionName
	 */
	public function setCollectionName($collectionName)
	{
		$this->collectionName = $collectionName;
	}
	
	/**
	 * Get Client
	 * @return MongoClient
	 */
	public function getClient()
	{
		return $this->client;
	}
	
	/**
	 * Set collection
	 */
	public function setCollection()
	{
		$this->collection = $this->client->{$this->databaseName}->{$this->collectionName};
	}
	
	/**
	 * Get collection
	 * @return MongoCollection
	 */
	public function getCollection()
	{
		return $this->collection;
	}
}
