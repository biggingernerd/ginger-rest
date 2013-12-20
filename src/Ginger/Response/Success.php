<?php
/**
 * Ginger/Response/Format/Json.php
 *
 * @author Martijn van Maasakkers
 */

namespace Ginger\Response;

use \Ginger\Response;

/**
 * Ginger Response Format JSON formatter
 */
class Success 
{

    /**
     * no_content function.
     *
     * @access public
     * @static
     * @return void
     */
    public static function no_content()
    {
        Response::$status = 204;
        Response::$data = array();
    }

    /**
     * created function.
     *
     * @access public
     * @static
     * @param array $item (default: array())
     * @return void
     */
    public static function created($item = array())
    {
        Response::$status = 201;
        Response::$data = array("item" => $item);
    }
}
