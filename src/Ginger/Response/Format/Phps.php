<?php
/**
 * Ginger/Response/Format/Phps.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */
 
namespace Ginger\Response\Format;

/**
 * Ginger Response Format PHPS formatter
 * 
 * @package Ginger\Library
 */
class Phps implements Format {
	
	/**
	 * Return string representation of $data
	 * 
	 * @param mixed $data
	 * @return string
	 */
	public static function Parse($data)
	{
		return serialize($data);
	}
	
}
