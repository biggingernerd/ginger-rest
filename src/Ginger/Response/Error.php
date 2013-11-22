<?php
/**
 * Ginger/Response/Format/Json.php
 *
 * @author Martijn van Maasakkers
 * @package Ginger
 */

namespace Ginger\Response;

use \Ginger\Response;

/**
 * Ginger Response Format JSON formatter
 *
 * @package Ginger\Library
 */
class Error 
{

    /**
     * not_found function.
     *
     * @access public
     * @static
     * @param bool $message (default: false)
     * @return void
     */
    public static function not_found($message = false)
    {
        if(!$message) {
            $message = "Resource not found";
        }

        Response::$status = 404;
        Response::$data = array("error" => $message);
    }

    /**
     * bad_request function.
     *
     * @access public
     * @static
     * @param array $data (default: array())
     * @return void
     */
    public static function bad_request($data = array())
    {
        $message = "Incorrect data sent";

        Response::$status = 400;
        Response::$data = array("error" => "Incorrect data sent", "messages" => $data);
    }
}
