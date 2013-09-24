<?php
/**
 * Ginger/Request/Route.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */
 
namespace Ginger\Request;

/**
 * Ginger Request Route Handler
 * 
 * @package Ginger\Library
 */
class Route {
	
	/**
	 * @var string $_route String representation of current route
	 */
	private $_route;
	
	/**
	 * @var string $_cleanRoute String representation of current route without leading /
	 */
	private $_cleanRoute;
	
	/**
	 * Read current route
	 * 
	 * @param string $path Total URL path
	 */
	public function __construct($path)
	{
		$routes 	= $GLOBALS['ginger']['routes'];
		
		$amountRoutes = count($routes);
		$totalLength = strlen($path);
		
		$found = null;
		for($i=0;$i<$amountRoutes;$i++)
		{
			$route = $routes[$i];
			
			$currentLength = strlen($routes[$i]);
			if(substr($path, 0, $currentLength) == $routes[$i] && $totalLength == $currentLength) {
				$this->_route = $routes[$i];
			} elseif(substr($path, 0, $currentLength+1) == $routes[$i]."/") {
				$this->_route = $routes[$i];
			}
			if($this->_route)
			{
				$this->getCleanRoute();
				break;
			}
		}	
		
		if(!$this->_route)
		{
			$this->_route = "/error/notfound";
		}
	}
	
	/**
	 * Get current route
	 * 
	 * @return string
	 */
	public function getRoute()
	{
		return $this->_route;
	}
	
	/**
	 * Get current clean route
	 * 
	 * @return string
	 */
	public function getCleanRoute()
	{
		if(!$this->_cleanRoute)
		{
			$this->_cleanRoute = substr($this->_route, 1);
		}
		
		return $this->_cleanRoute;
	}
}
