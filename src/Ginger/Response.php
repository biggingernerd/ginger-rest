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
	 * _data
	 * 
	 * (default value: null)
	 * 
	 * @var mixed
	 * @access private
	 */
	private $_data = null;
	
	/**
	 * _status
	 * 
	 * (default value: 200)
	 * 
	 * @var int
	 * @access private
	 */
	private $_status = 200;
	
	/**
	 * _callback
	 * 
	 * (default value: null)
	 * 
	 * @var mixed
	 * @access private
	 */
	private $_callback = null;
	
	/**
	 * Constructor. Sets default values and sends data.
	 * @param \Ginger\Request $request
	 */
	public function __construct()
	{
		
		$this->setCallback();
		$this->setFormat();
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
		$data = $this->_parseDataToString($this->_data);
		$format = $this->_allowedFormats[$this->_format];
		
		// Send status header
		header($this->getStatus(), true, $this->getStatus());
		
		// Send content type header
		header("Content-Type: ".$format['mimetype']."; charset=utf-8");
		
		echo $data;
		die();
	}

	/**
	 * setFormat function.
	 * 
	 * @access public
	 * @param bool $format (default: false)
	 * @return void
	 */
	public function setFormat($format = false)
	{
		if(!$format)
		{
			$format = \Ginger\System\Parameters::$format;
		}
		$this->_format 		= $format;
		
		if(!isset($this->_allowedFormats[$this->_format]))
		{
			$this->_format = $this->_defaultFormat;
		}
	}
	
	/**
	 * getFormat function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getFormat()
	{
		return $this->_format;
	}
	
	/**
	 * setData function.
	 * 
	 * @access public
	 * @param mixed $data
	 * @return void
	 */
	public function setData($data)
	{
		$this->_data = $data;
	}
	
	/**
	 * getData function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getData()
	{
		return $this->_data;
	}
	
	/**
	 * setStatus function.
	 * 
	 * @access public
	 * @param mixed $status
	 * @return void
	 */
	public function setStatus($status)
	{
		$this->_status = $status;
	}
	
	/**
	 * getStatus function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getStatus()
	{
		return $this->_status;
	}
	
	/**
	 * setCallback function.
	 * 
	 * @access public
	 * @param bool $callback (default: false)
	 * @return void
	 */
	public function setCallback($callback = false)
	{
		if(!$callback)
		{
			$callback = \Ginger\System\Parameters::$callback;
		}
		$this->_callback = $callback;
	}
	
	/**
	 * getCallback function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getCallback()
	{
		return $this->_callback;
	}
}
