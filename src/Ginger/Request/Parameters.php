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
	private $allParameters 	= array();

	/**
	 * @var array $_filterParameters Filter parameters (from Url)
	 */
	private $filterParameters 	= array();
	
	/**
	 * @var array $_dataParameters All data parameters (from postfields object)
	 */
	private $dataParameters 	= array();
	
	/**
	 * Reads parameters
	 * 
	 * @param Url $url
	 * @param Route $route
	 */
	public function __construct(Url $url, Route $route)
	{
		$this->getParams($url->path, $route->getRoute());
	}
	
	/**
	 * Get all params
	 * @param string $currentPath
	 * @param string $route
	 */
	private function getParams($currentPath, $route)
	{
		$path = substr($currentPath, strlen($route));
		$path = (substr($path, 0, 1) == "/") ? substr($path, 1): $path;
		$path = (substr($path, -1) == "/") ? substr($path, 0, -1): $path;
		
		$this->getFilterParams($path);
		$this->getDataParams();
		$this->cleanReservedParams();
	}
	
	/**
	 * Get all "filter" params from uri path
	 * 
	 * @param string $path
	 */
	private function getFilterParams($path)
	{
		if(!$path)
		{
			$path = "";
		}
		
		$parts = explode("/", $path);
		if($path == "")
		{
		
		} elseif(count($parts) == 1) {
			$this->filterParameters['id'] = $parts[0];
		} else {
			foreach($parts as $key => $part)
			{
				if(($key % 2) == 1)
				{	
					$this->filterParameters[$parts[$key-1]] = urldecode($part);
				} else {
					$this->filterParameters[$part] = "";
				}
			}
		}
		
		$this->filterParameters = array_merge($_GET, $this->filterParameters);
		$this->parseParameterValues();
		
	}
	
	/**
	 * Loop through filter parameters and formats the values for internals
	 */
	private function parseParameterValues()
	{
		$params = $this->filterParameters;
		
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
			
			$this->filterParameters[$key] = $input;
		}
		
	}
	
	/**
	 * Loop through filter parameters and formats the values for internals
	 */
	private function parseDataParameterValues()
	{
		$params = $this->dataParameters;
		
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
			
			$this->dataParameters[$key] = $input;
		}
		
	}
	
	
	/**
	 * Get data params based on request method
	 */
	public function getDataParams()
	{
		$method = $_SERVER['REQUEST_METHOD'];
		
		switch($method)
		{
			case "POST":
				$this->dataParameters = $_POST;
				break;
				
			case "PUT":
			case "DELETE":
				parse_str(file_get_contents("php://input"), $this->dataParameters);
				break;
		}
		
		$this->parseDataParameterValues();
		
	}
	
	/**
	 * Read all reserved params and add them to class value
	 */
	public function cleanReservedParams()
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
			if(isset($this->filterParameters[$param]))
			{
				\Ginger\System\Parameters::$$paramKey = $this->filterParameters[$param];
				unset($this->filterParameters[$param]);
			}
		}
		
		if(isset($this->dataParameters["oauth_token"]))
		{
			\Ginger\System\Parameters::$oauth_token = $this->dataParameters["oauth_token"];
			unset($this->dataParameters["oauth_token"]);
		}
		
		
	}

	/**
	 * Return all filter parameters
	 * 
	 * @return array
	 */
	public function getFilterParameters()
	{
		return $this->filterParameters;
	}
	
	/**
	 * Return all data parameters
	 * 
	 * @return array
	 */
	public function getDataParameters()
	{
		return $this->dataParameters;
	}
	
}
