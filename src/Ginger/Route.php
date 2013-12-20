<?php
/**
 * Ginger/Route.php
 *
 * @author Martijn van Maasakkers
 */

namespace Ginger;

/**
 * Ginger Route Object
 */
class Route
{
    /**
     * uri
     * 
     * (default value: '/')
     * 
     * @var string
     * @access public
     */
    public $route = '/';
    /**
     * route
     * 
     * (default value: '')
     *
     * @var string
     * @access public
     */
    public $resource = '';
    /**
     * path
     * 
     * (default value: '')
     * 
     * @var string
     * @access public
     */
    public $path = '';
    
    /**
     * __construct function.
     * 
     * @access public
     * @param string $uri (default: '/')
     * @param string $path (default: '')
     * @return void
     */
    public function __construct($uri = '/', $path = '')
    {
        $this->route = $uri;
        $this->path = $path;
        
        $this->cleanRoute();
        $this->getRoute();
        $this->getPath();
    }
    
    
    /**
     * cleanRoute function.
     * 
     * @access private
     * @return void
     */
    private function cleanRoute()
    {
        $uri = "";
        if(strlen($this->route) > 0 && $this->route != "/") {
            $first = substr($this->route, 0, 1);
            $last = substr($this->route, -1);
            if($first != "/") {
                $uri = "/";
            }
            $uri .= $this->route;
            if($last == "/") {
                $uri = substr($uri, 0, -1);
            }
            
            $this->route = $uri;
        }
    }
    
    /**
     * getRoute function.
     * 
     * @access private
     * @return void
     */
    private function getRoute()
    {
        $route = $this->route;
        $first = substr($route, 0, 1);
        $last = substr($route, -1);
        if($first == "/") {
            $route = substr($route, 1);
        }
        if($last == "/") {
            $route = substr($route, 0, -1);
        }
        $this->resource = $route;
        
    }
    
    /**
     * getPath function.
     * 
     * @access private
     * @return void
     */
    private function getPath()
    {
        $path = "";
        if(strlen($this->path) > 0) {
            $path = $this->path;    
        } else {
            $path = $this->route;
        }

        if(substr($path, -1) !== "/") {
            $path .= "/";
        }

        $this->path = $path;
    }
}
