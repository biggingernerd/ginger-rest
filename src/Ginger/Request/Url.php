<?php
/**
 * Ginger/Request/Url.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */
 
namespace Ginger\Request;

/**
 * Ginger Request URL Handler
 * 
 * @package Ginger\Library
 */
class Url extends \Ginger\Url {
	
	/**
	 * Read Current URL
	 */
	public function __construct()
	{
		$check = "/v2";
		$uri = $_SERVER['REQUEST_URI'];
		if(substr($uri, 0, strlen($check)) == $check)
		{
			$uri = substr($uri, strlen($check));
		}
		$url = "http" . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=="on") ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $uri;
		
		parent::__construct($url);
	}
}
