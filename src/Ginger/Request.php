<?php
/**
 * Ginger/Request.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */
 
namespace Ginger;

use \Ginger\Request\Parameters;
use \Ginger\Request\Url;
use \Ginger\Request\Route;
use \Ginger\Registry;
use \Ginger\Response;

/**
 * Ginger Request Handler
 * 
 * @package Ginger\Library
 */
class Request {
	
	/**
	 * Contains the parameters object
	 * 
	 * @var \Ginger\Request\Parameters
	 */
	private $_parameters;
	
	/**
	 * Contains the current URL object
	 * 
	 * @var \Ginger\Request\Url
	 */
	private $_url;
	
	/**
	 * @var \Ginger\Request\Route $_route Route object
	 */
	private $_route;
	
	/**
	 * @var string $_method Request Method
	 */
	private $_method;
	
	/**
	 * @var string $_action Action
	 */
	private $_action;
	
	
	/**
	 * _access
	 * 
	 * (default value: false)
	 * 
	 * @var bool
	 * @access private
	 */
	private $_access = false;
	
	
	/**
	 * _profile
	 * 
	 * (default value: false)
	 * 
	 * @var array
	 * @access private
	 */
	private $_profile = false;
	
	
	/**
	 * Constructor function
	 */
	public function __construct($check = true)
	{
		Registry::set("Request", $this);
		
		$this->_url 		= 	new Url();
		$this->_route		=	new Route($this->getUrl()->path);
		$this->_parameters 	= 	new Parameters($this->_url, $this->_route);
		$this->_action		= 	$this->getAction();
	}
	
	/**
	 * Load file and dispatch to response
	 */
	public function go()
	{
		// Check if handler file exists
		if($this->_route->getCleanRoute() == "")
		{
			$file = $this->getAction().$this->getExtension();
		} else {
			$file = $this->_route->getCleanRoute()."/".$this->getAction().$this->getExtension();
		}
		
		$fullFilePath = stream_resolve_include_path($file);
		
		if($fullFilePath)
		{
			include($fullFilePath);	
		} else {
			throw new \Exception("Not found", 404);
		}
		
		$response = new Response($this);
	}
	
	/**
	 * Return parameter object
	 * 
	 * @return \Ginger\Request\Parameters
	 */
	public function getParameters()
	{
		return $this->_parameters;
	}
	
	/**
	 * Return the filter array 
	 *  
	 * @return array
	 */
	public function getFilterParameters()
	{
		return $this->_parameters->getFilterParameters();
	}
	
	/**
	 * Return the data array
	 *
	 * @return array
	 */
	public function getDataParameters()
	{
		return $this->_parameters->getDataParameters();
	}
	
	/**
	 * Return Route object
	 * 
	 * @return \Ginger\Request\Route
	 */
	public function getRoute()
	{
		return $this->_route;
	}
	
	/**
	 * Return URL object
	 * 
	 * @return \Ginger\Request\Url
	 */
	public function getUrl()
	{
		return $this->_url;
	}
	
	/**
	 * Return Request Method
	 * 
	 * @return string
	 */
	public function getMethod()
	{
		if(!isset($this->_method))
		{
			$this->_method = $_SERVER['REQUEST_METHOD'];
		}
		
		return $this->_method;
	}
	
	/**
	 * getProfile function.
	 * 
	 * @access public
	 * @return array
	 */
	public function getProfile()
	{
		return $this->_profile;
	}
	
	/**
	 * Return current action
	 * 
	 * @return string
	 */
	public function getAction()
	{
		$action = "index";
		switch($_SERVER['REQUEST_METHOD'])
		{
			case "GET":
				if(count($this->getParameters()->getFilterParameters()) == 0)
				{
					$action = "index";
				} else {
					$action = "get";
				}
				break;
	
			case "POST":
				$action = "post";
				break;
	
			case "PUT":
				$action = "put";
				break;
	
			case "DELETE":
				$action = "delete";
				break;
	
			case "HEAD":
				$action = "head";
				break;
	
			case "OPTIONS":
				$action = "options";
				break;
	
		}
	
		return $action;
	}
	
	/**
	 * Return module file extension
	 * 
	 * @return string
	 */
	public function getExtension()
	{
		return ".php";
	}
}
