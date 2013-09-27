<?php
/**
 * Ginger/Url.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */
 
namespace Ginger;

/**
 * Ginger URL
 * 
 * @package Ginger\Library
 */
class Url {
	
	/**
	 * scheme
	 *
	 * Used scheme (usually http or https)
	 *
	 * @var string
	 * @access public
	 */
	public $scheme;
	
	/**
	 * host
	 * 
	 * Host
	 * 
	 * @var string
	 * @access public
	 */
	public $host;
	
	/**
	 * path
	 *
	 * URL Path
	 *
	 * @var string
	 * @access public
	 */
	public $path;
	
	/**
	 * query
	 *
	 * Raw query
	 *
	 * @var string
	 * @access public
	 */
	public $query;
	
	/**
	 * queryParts
	 * 
	 * All queryparts based in key=value
	 * 
	 * @var array
	 * @access public
	 */
	public $queryParts = array();
	
	/**
	 * Parse url into object
	 * 
	 * @param string $url URL String
	 */
	public function __construct($url)
	{
		$url = parse_url($url);
		
		$this->scheme 	=	$url['scheme'];
		$this->host 	=	$url['host'];
		$this->path		=	$url['path'];
		$this->query	=	(isset($url['query'])) ? $url['query'] : "";
		
		parse_str($this->query, $this->queryParts);
	}
	
	public function getParameter($key)
	{
		if(isset($this->queryParts[$key]))
		{
			return $this->queryParts[$key];
		} else {
			return false;
		}
	}
}
