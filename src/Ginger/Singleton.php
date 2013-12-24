<?php
/**
 * Singleton class.
 */

namespace Ginger;

/**
 * Singleton class.
 */
class Singleton
{
    /**
     * instances
     * 
     * (default value: array())
     * 
     * @var array
     * @access private
     * @static
     */
    private static $instances = array();
    /**
     * __construct function.
     * 
     * @access protected
     * @return void
     */
    protected function __construct() {}
    /**
     * __clone function.
     * 
     * @access protected
     * @return void
     */
    protected function __clone() {}
    /**
     * __wakeup function.
     * 
     * @access public
     * @return void
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }

    /**
     * getInstance function.
     * 
     * @access public
     * @static
     * @return void
     */
    public static function getInstance()
    {
        $cls = get_called_class(); // late-static-bound class name
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
        }
        return self::$instances[$cls];
    }
}