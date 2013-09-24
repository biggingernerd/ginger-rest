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
class Error {
	
	/**
	 * not_found function.
	 * 
	 * @access public
	 * @static
	 * @param bool $message (default: false)
	 * @return void
	 */
	public static function not_found($message = false)
	{
		if(!$message)
		{
			$message = "Resource not found";	
		}
		
		Response::$Status = 404;
		Response::$Data = array("error" => $message);
	}

	/**
	 * bad_request function.
	 * 
	 * @access public
	 * @static
	 * @param array $data (default: array())
	 * @return void
	 */
	public static function bad_request($data = array())
	{
		$message = "Incorrect data sent";
	
		Response::$Status = 400;
		Response::$Data = array("error" => "Incorrect data sent", "messages" => $data);
	}

}
