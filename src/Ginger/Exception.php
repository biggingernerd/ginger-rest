<?php
/**
 * Ginger/Exception.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 * @todo Actually formatting the error message here would be better
 */

namespace Ginger;


/**
 * Ginger Exception Handler
 * 
 * @package Ginger\Library
 */
class Exception extends \Exception 
{
	public function __construct($message, $code)
	{
    	$response = new Response();
    	$response->setStatus($code);
    	$response->setData(array("error" => $message));
    	$response->send();
	}
}
