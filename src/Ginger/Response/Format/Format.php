<?php
/**
 * Ginger/Response/Format/Interface.php
 *
 * @author Martijn van Maasakkers
 * @package Ginger
 */

namespace Ginger\Response\Format;

/**
 * Ginger Response Format Interface
 *
 * @package Ginger\Library
 */
interface Format 
{
    /**
     * Return string representation of $data
     *
     * @param mixed $data
     * @return string
     */
    public static function Parse($data);

}
