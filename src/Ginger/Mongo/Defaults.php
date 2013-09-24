<?php
/**
 * Ginger/Mongo/Defaults.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */
 
namespace Ginger\Mongo;

use \Ginger\System\Parameters;


/**
 * Ginger Mongo Defaults Handler
 * 
 * @package Ginger\Library
 */
class Defaults {
	/**
	 * @var int $limit Max returned results
	 */
	public $limit;
	/**
	 * @var int $offset Offset
	 */
	public $offset;
	/**
	 * @var string $sort Sort field
	 */
	public $sort;
	/**
	 * @var string $direction Sort direction
	 */
	public $direction;
	
	/**
	 * Read parameters from system
	 */
	public function __construct()
	{
		$this->limit 		= Parameters::$limit;
		$this->offset 		= Parameters::$offset;
		$this->sort 		= Parameters::$sort;
		$this->direction 	= Parameters::$direction;
	}
}