<?php
/**
 * Ginger/Response/Format/Json.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */
 
namespace Ginger\Response\Format;

/**
 * Ginger Response Format JSON formatter
 * 
 * @package Ginger\Library
 */
class Jsonp implements Format {
	
	/**
	 * Return string representation of $data
	 *
	 * @param mixed $data
	 * @return string
	 */
	public static function Parse($data)
	{
		return \Ginger\Response::$Callback."(".json_encode($data).");";
	}
	
}

