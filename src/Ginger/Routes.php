<?php
/**
 * Ginger/Routes.php
 *
 * @author Martijn van Maasakkers
 */

namespace Ginger;

/**
 * Ginger Routes Object
 */
class Routes
{
    /**
     * routes
     * 
     * (default value: array())
     * 
     * @var array
     * @access private
     * @static
     */
    private static $routes = array();
    
    /**
     * get function.
     * 
     * @access public
     * @static
     * @return array
     */
    public static function get()
    {
        self::sortRoutes();
        return self::$routes;
    }
    
    /**
     * set function.
     * 
     * @access public
     * @static
     * @param mixed $uri
     * @param bool $path (default: false)
     * @return void
     */
    public static function set($uri, $path = false)
    {
        $route = new \Ginger\Route($uri, $path);
        self::$routes[$route->route] = $route;
    }
    
    /**
     * sortRoutes function.
     * 
     * @access private
     * @static
     * @return void
     */
    private static function sortRoutes()
    {
        krsort(self::$routes);
    }
    
    /**
     * detect function.
     * 
     * @access public
     * @static
     * @param string $uri
     * @return false|\Ginger\Route
     */
    public static function detect($uri)
    {
        $routes = self::get();
        $found = false;
        foreach($routes as $key => $route) {
            $currentLength = strlen($route->route);
            if(substr($uri, 0, $currentLength) == $route->route) {
                $found = $route;
                break;
            }
        }
        
        return $found;
    }
}
