<?php
/**
 * Ginger/Response.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */
 
namespace Ginger;

/**
 * Ginger Response Handler
 * 
 * @package Ginger\Library
 */
class Response {
	
	/**
	 * The data object passed from the module file
	 * @todo This doesn't belong here?
	 * @var mixed
	 */
	public static $data;
	
	/**
	 * Status code to be returned
	 * 
	 * @var int
	 * @todo This doesn't belong here?
	 */
	public static $status = 200;
	
	/**
	 * Callback function used for jsonp calls
	 * 
	 * @var string
	 * @todo This doesn't belong here?
	 */
	public static $callback;
	
	/**
	 * Action name
	 *
	 * @var string
	 * @todo This doesn't belong here?
	 */
	public static $action;
	
	
	/**
	 * Request object
	 * 
	 * @var \Ginger\Request
	 */
	private $_request;
	
	/**
	 * Format value
	 * @var string
	 */
	private $_format;
	
	/**
	 * All allowed formats + classes and mimetypes
	 * @var array
	 */
	private $_allowedFormats = array("json" => array(
													"class" => "Json", 
													"mimetype" => "application/json"
												),
									 "jsonp" => array(
													"class" => "Jsonp",
													"mimetype" => "application/json"
												),
									 "xml"	=> array(
													"class" => "Xml",
													"mimetype" => "application/xml"
												),
									 "phps"	=> array(
													"class" => "Phps",
													"mimetype" => "text/plain"
												)
									 );
	
	/**
	 * Default format value
	 * @var string
	 */
	private $_defaultFormat = "json";
	
	/**
	 * Constructor. Sets default values and sends data.
	 * @param \Ginger\Request $request
	 */
	public function __construct(\Ginger\Request $request)
	{
		$this->_request 	= $request;
		$this->_format 		= \Ginger\System\Parameters::$format;
		
		if(!isset($this->_allowedFormats[$this->_format]))
		{
			$this->_format = $this->_defaultFormat;
		}

		self::$Callback = \Ginger\System\Parameters::$callback;
		
		$this->send();
		
	}
	
	/**
	 * Passes data to one of the format interfaces and returns string value
	 * 
	 * @param string $data
	 */
	private function _parseDataToString($data)
	{
		$format = $this->_allowedFormats[$this->_format];
		$className = "\\Ginger\\Response\\Format\\".$format['class'];
		return $className::Parse($data);
		
	}
	
	/**
	 * Sets status header, content-type header and outputs data.
	 * Die is there for making sure nothing else is processed after this.
	 */
	public function send()
	{
		$data = $this->_parseDataToString(self::$data);
		$format = $this->_allowedFormats[$this->_format];
		
		// Send status header
		header(self::$status, true, self::$status);
		
		// Send content type header
		header("Content-Type: ".$format['mimetype']."; charset=utf-8");
		
		echo $data;
		die();
	}
	
}
