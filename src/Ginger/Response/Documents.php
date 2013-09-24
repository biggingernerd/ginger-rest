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
	
	public $total;
	public $limit;
	public $offset;
	public $sort;
	public $direction;
	public $filter;
	public $items;

	public function __construct($items, $total, $filter)
	{
		$this->set_items($items);
		$this->set_total($total);
		$this->set_filter($filter);
		
		$this->set_limit(Parameters::$limit);
		$this->set_offset(Parameters::$offset);
		$this->set_sort(Parameters::$sort);
		$this->set_direction(Parameters::$direction);
	}
		
	public function set_total($value){
	    $this->total = $value;
	}
	public function set_limit($value){
	    $this->limit = $value;
	}
	public function set_offset($value){
	    $this->offset = $value;
	}
	public function set_sort($value){
	    $this->sort = $value;
	}
	public function set_direction($value){
	    $this->direction = $value;
	}
	public function set_filter($value){
	    $this->filter = $value;
	}
	public function set_items($value){
	    $this->items = $value;
	}
	public function get_total(){
	    return $this->total;
	}
	public function get_limit(){
	    return $this->limit;
	}
	public function get_offset(){
	    return $this->offset;
	}
	public function get_sort(){
	    return $this->sort;
	}
	public function get_direction(){
	    return $this->direction;
	}
	public function get_filter(){
	    return $this->filter;
	}
	public function get_items(){
	    return $this->items;
	}

}
