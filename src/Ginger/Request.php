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
    private $parameters;
    
    /**
     * Contains the current URL object
     * 
     * @var \Ginger\Request\Url
     */
    private $url;
    
    /**
     * @var \Ginger\Request\Route $route Route object
     */
    private $route;
    
    /**
     * @var string $method Request Method
     */
    private $method;
    
    /**
     * @var string $action Action
     */
    private $action;
    
    
    /**
     * access
     * 
     * (default value: false)
     * 
     * @var bool
     * @access private
     */
    private $access = false;
    
    
    /**
     * profile
     * 
     * (default value: false)
     * 
     * @var array
     * @access private
     */
    private $profile = false;
    
    /**
     * data
     * 
     * (default value: false)
     * 
     * @var bool
     * @access private
     */
    private $data = false;
	
    /**
     * response
     * 
     * (default value: null)
     * 
     * @var mixed
     * @access private
     */
    private $response = null;
	
    /**
     * Constructor function
     */
    public function __construct()
    {
        Registry::set("Request", $this);
        
        $this->url 		    = new Url();
        $this->route        = new Route($this->getUrl()->path);
        $this->parameters   = new Parameters($this->url, $this->route);
        $this->action       = $this->getAction();
        $this->response     = new Response();
    }
	
	/**
	 * Load file and dispatch to response
	 */
	public function go()
	{
		// Check if handler file exists
		if($this->route->getCleanRoute() == "")
		{
			$file = $this->getAction().$this->getExtension();
		} else {
			$file = $this->route->getCleanRoute()."/".$this->getAction().$this->getExtension();
		}
		
		$fullFilePath = stream_resolve_include_path($file);
		
		if($fullFilePath)
		{
			include($fullFilePath);	
		} else {
			throw new \Exception("Not found", 404);
		}
		
		$this->getResponse()->send();
	}
	
	/**
	 * Return parameter object
	 * 
	 * @return \Ginger\Request\Parameters
	 */
	public function getParameters()
	{
		return $this->parameters;
	}
	
	/**
	 * Return the filter array 
	 *  
	 * @return array
	 */
	public function getFilterParameters()
	{
		return $this->parameters->getFilterParameters();
	}
	
	/**
	 * Return the data array
	 *
	 * @return array
	 */
	public function getDataParameters()
	{
		return $this->parameters->getDataParameters();
	}
	
	/**
	 * Return Route object
	 * 
	 * @return \Ginger\Request\Route
	 */
	public function getRoute()
	{
			return $this->route;
	}
	
	/**
	 * Return URL object
	 * 
	 * @return \Ginger\Request\Url
	 */
	public function getUrl()
	{
		return $this->url;
	}
	
	/**
	 * Return Request Method
	 * 
	 * @return string
	 */
	public function getMethod()
	{
		if(!isset($this->method))
		{
			$this->method = $_SERVER['REQUEST_METHOD'];
		}
		
		return $this->method;
	}
	
	/**
	 * getProfile function.
	 * 
	 * @access public
	 * @return array
	 */
	public function getProfile()
	{
		return $this->profile;
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
	
	/**
	 * setResponseData function.
	 * 
	 * @access public
	 * @param array $data (default: array())
	 * @return void
	 */
	public function setResponseData($data = array())
	{
		$this->data = $data;
	}
	
	/**
	 * getResponseData function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getResponseData()
	{
		return $this->data;
	}
	
	/**
	 * getResponse function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getResponse()
	{
		return $this->response;
	}
}
