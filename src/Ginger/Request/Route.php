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
class Route
{

    /**
     * @var string $route String representation of current route
     */
    private $route;

    /**
     * @var string $cleanRoute String representation of current route without leading /
     */
    private $cleanRoute;

    /**
     * Read current route
     *
     * @param string $path Total URL path
     */
    public function __construct($path)
    {
        $routes  = $GLOBALS['ginger']['routes'];

        $amountRoutes = count($routes);
        $totalLength = strlen($path);

        $found = null;
        for($i=0;$i<$amountRoutes;$i++) {
            $route = $routes[$i];

            $currentLength = strlen($routes[$i]);
            if(substr($path, 0, $currentLength) == $routes[$i] && $totalLength == $currentLength) {
                $this->route = $routes[$i];
            } elseif(substr($path, 0, $currentLength+1) == $routes[$i]."/") {
                $this->route = $routes[$i];
            }
            if($this->route) {
                $this->getCleanRoute();
                break;
            }
        }

        if(!$this->route) {
            $this->route = "/error/notfound";
        }
    }

    /**
     * Get current route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Get current clean route
     *
     * @return string
     */
    public function getCleanRoute()
    {
        if(!$this->cleanRoute) {
            $this->cleanRoute = substr($this->route, 1);
        }

        return $this->cleanRoute;
    }
}
