<?php
/**
 * Ginger/Response/Default.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */
 
namespace Ginger\Response;

use \Ginger\System\Parameters;

/**
 * Ginger Default (Document) Response
 * 
 * @package Ginger\Library
 */
class Documents {
	
	/**
	 * total
	 * 
	 * @var mixed
	 * @access public
	 */
	public $total;
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
	 * filter
	 * 
	 * @var mixed
	 * @access public
	 */
	public $filter;
	/**
	 * items
	 * 
	 * @var mixed
	 * @access public
	 */
	public $items;

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @param mixed $items
	 * @param mixed $total
	 * @param mixed $filter
	 * @return void
	 */
	public function __construct($items, $total, $filter)
	{
		$this->setItems($items);
		$this->setTotal($total);
		$this->setFilter($filter);
		
		$this->setLimit(Parameters::$limit);
		$this->setOffset(Parameters::$offset);
		$this->setSort(Parameters::$sort);
		$this->setDirection(Parameters::$direction);
	}
		
	/**
	 * setTotal function.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return void
	 */
	public function setTotal($value){
	    $this->total = $value;
	}
	/**
	 * setLimit function.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return void
	 */
	public function setLimit($value){
	    $this->limit = $value;
	}
	/**
	 * setOffset function.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return void
	 */
	public function setOffset($value){
	    $this->offset = $value;
	}
	/**
	 * setSort function.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return void
	 */
	public function setSort($value){
	    $this->sort = $value;
	}
	/**
	 * setDirection function.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return void
	 */
	public function setDirection($value){
	    $this->direction = $value;
	}
	/**
	 * setFilter function.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return void
	 */
	public function setFilter($value){
	    $this->filter = $value;
	}
	/**
	 * setItems function.
	 * 
	 * @access public
	 * @param mixed $value
	 * @return void
	 */
	public function setItems($value){
	    $this->items = $value;
	}
	/**
	 * getTotal function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getTotal(){
	    return $this->total;
	}
	/**
	 * getLimit function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getLimit(){
	    return $this->limit;
	}
	/**
	 * getOffset function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getOffset(){
	    return $this->offset;
	}
	/**
	 * getSort function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getSort(){
	    return $this->sort;
	}
	/**
	 * getDirection function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getDirection(){
	    return $this->direction;
	}
	/**
	 * getFilter function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getFilter(){
	    return $this->filter;
	}
	/**
	 * getItems function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getItems(){
	    return $this->items;
	}

}
