<?php
/**
 * Ginger/Response/Format/Json.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */
 
namespace Ginger\Response;

use \Ginger\Response;

/**
 * Ginger Response Format JSON formatter
 * 
 * @package Ginger\Library
 */
class Success {
	
	
	/**
	 * no_content function.
	 * 
	 * @access public
	 * @static
	 * @return void
	 */
	public static function no_content()
	{
		Response::$Status = 204;
		Response::$Data = array();
	}

	/**
	 * created function.
	 * 
	 * @access public
	 * @static
	 * @param array $item (default: array())
	 * @return void
	 */
	public static function created($item = array())
	{
		Response::$Status = 201;
		Response::$Data = array("item" => $data);
	}

}
