<?php
/**
 * Ginger/Response/Format/Json.php
 *
 * @author Martijn van Maasakkers
 */

namespace Ginger\Response\Format;

/**
 * Ginger Response Format JSON formatter
 */
class Json implements Format 
{
    /**
     * Return string representation of $data
     *
     * @param mixed $data
     * @return string
     */
    public static function Parse($data)
    {
        return json_encode($data);
    }
}
