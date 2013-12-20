<?php
/**
 * Ginger/Response/Format/Jsonp.php
 *
 * @author Martijn van Maasakkers
 */

namespace Ginger\Response\Format;

/**
 * Ginger Response Format JSONp formatter
 */
class Jsonp implements Format 
{

    /**
     * Return string representation of $data
     *
     * @param mixed $data
     * @return string
     */
    public static function Parse($data)
    {
        return \Ginger\Response::$callback."(".json_encode($data).");";
    }
}
