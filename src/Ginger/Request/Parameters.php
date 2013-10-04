<?php
/**
 * Ginger/Request/Parameters.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */
 
namespace Ginger\Request;


use \Ginger\Request\Url;
use \Ginger\Request\Route;

/**
 * Ginger Request Parameters Handler
 * 
 * @package Ginger\Library
 */
class Parameters {
	
	/**
	 * @var array $_allParameters All parameters
	 */
	private $_allParameters 	= array();

	/**
	 * @var array $_filterParameters Filter parameters (from Url)
	 */
	private $_filterParameters 	= array();
	
	/**
	 * @var array $_dataParameters All data parameters (from postfields object)
	 */
	private $_dataParameters 	= array();
	
	/**
	 * Reads parameters
	 * 
	 * @param Url $url
	 * @param Route $route
	 */
	public function __construct(Url $url, Route $route)
	{
		$this->_getParams($url->path, $route->getRoute());
	}
	
	/**
	 * Get all params
	 * @param string $currentPath
	 * @param string $route
	 */
	private function _getParams($currentPath, $route)
	{
		$path = substr($currentPath, strlen($route));
		$path = (substr($path, 0, 1) == "/") ? substr($path, 1): $path;
		$path = (substr($path, -1) == "/") ? substr($path, 0, -1): $path;
		
		$this->_getFilterParams($path);
		$this->_getDataParams();
		$this->_cleanReservedParams();
	}
	
	/**
	 * Get all "filter" params from uri path
	 * 
	 * @param string $path
	 */
	private function _getFilterParams($path)
	{
		if(!$path)
		{
			$path = "";
		}
		
		$parts = explode("/", $path);
		if($path == "")
		{
		
		} elseif(count($parts) == 1) {
			$this->_filterParameters['id'] = $parts[0];
		} else {
			foreach($parts as $key => $part)
			{
				if(($key % 2) == 1)
				{	
					$this->_filterParameters[$parts[$key-1]] = urldecode($part);
				} else {
					$this->_filterParameters[$part] = "";
				}
			}
		}
		
		$this->_filterParameters = array_merge($_GET, $this->_filterParameters);
		$this->_parseParameterValues();
		
	}
	
	/**
	 * Loop through filter parameters and formats the values for internals
	 */
	private function _parseParameterValues()
	{
		$params = $this->_filterParameters;
		
		foreach($params as $key => $input)
		{
			if(strpos($input, "|"))
			{
				$input = explode("|", $input);
			}
			if($input == "false")
			{
				$input = false;
			}
			if($input == "true")
			{
				$input = true;
			}
			if(is_numeric($input))
			{
				$input = (float)$input;
				if(!strpos($input, "."))
				{
					$input = (int)$input;
				}
			}
			
			$this->_filterParameters[$key] = $input;
		}
		
	}
	
	/**
	 * Loop through filter parameters and formats the values for internals
	 */
	private function _parseDataParameterValues()
	{
		$params = $this->_dataParameters;
		
		foreach($params as $key => $input)
		{
			if(strpos($input, "|"))
			{
				$input = explode("|", $input);
			}
			if($input == "false")
			{
				$input = false;
			}
			if($input == "true")
			{
				$input = true;
			}
			if(is_numeric($input))
			{
				$input = (float)$input;
				if(!strpos($input, "."))
				{
					$input = (int)$input;
				}
			}
			
			$this->_dataParameters[$key] = $input;
		}
		
	}
	
	
	/**
	 * Get data params based on request method
	 */
	public function _getDataParams()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		
		switch($method)
		{
			case "POST":
				$this->_dataParameters = $_POST;
				break;
				
			case "PUT":
			case "DELETE":
				parse_str(file_get_contents("php://input"), $this->_dataParameters);
				break;
		}
		
		$this->_parseDataParameterValues();
		
	}
	
	/**
	 * Read all reserved params and add them to class value
	 */
	public function _cleanReservedParams()
	{
		$params = array(
			"_format"        => "format",
			"_limit"         => "limit",
			"_offset"        => "offset",
			"_sort"          => "sort",
			"_direction"     => "direction",
			"_debug"         => "debug",
			"_locale"        => "locale",
			"_template"		 =>	"template",
			"oauth_token"    => "oauth_token",
			"callback"       => "callback"
		);
		
		foreach($params as $param => $paramKey)
		{
			if(isset($this->_filterParameters[$param]))
			{
				\Ginger\System\Parameters::$$paramKey = $this->_filterParameters[$param];
				unset($this->_filterParameters[$param]);
			}
		}
		
		if(isset($this->_dataParameters["oauth_token"]))
		{
			\Ginger\System\Parameters::$oauth_token = $this->_dataParameters["oauth_token"];
			unset($this->_dataParameters["oauth_token"]);
		}
		
		
	}

	/**
	 * Return all filter parameters
	 * 
	 * @return array
	 */
	public function getFilterParameters()
	{
		return $this->_filterParameters;
	}
	
	/**
	 * Return all data parameters
	 * 
	 * @return array
	 */
	public function getDataParameters()
	{
		return $this->_dataParameters;
	}
	
}
