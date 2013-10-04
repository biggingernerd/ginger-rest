<?php
/**
 * Ginger/System/Parameters.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */

namespace Ginger\System;


/**
 * Ginger System Parameters Handler
 * 
 * @package Ginger\Library
 */
class Parameters {
		
	/**
	 * @var string $format Format value (default "json")
	 */
	public static $format = "json";
	
	/**
	 * @var int $limit Limit value (default "10")
	 */
	public static $limit = 10;
	
	/**
	 * @var int $offset Offset value (default "0")
	 */
	public static $offset = 0;
	
	/**
	 * @var string $sort Sort value (default "created")
	 */
	public static $sort = "created";
	
	/**
	 * @var string $direction Sort Direction value (default "asc")
	 */
	public static $direction = "asc";
	
	/**
	 * @var string $debug Debug value (default "false")
	 */
	public static $debug = false;
	
	/**
	 * @var string $callback Callback value for jsonp (default "null")
	 */
	public static $callback;
	
	/**
	 * @var string $template Template file for html parser (default "null")
	 */
	public static $template;
	
	/**
	 * @var string $locale Locale value (default "null")
	 */
	public static $locale;

	/**
	 * @var string $oauth_token Oauth token (default "null")
	 */
	public static $oauth_token;
}
