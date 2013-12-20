<?php
/**
 * Ginger/Response/Format/Interface.php
 *
 * @author Martijn van Maasakkers
 */

namespace Ginger\Response\Format;

/**
 * Ginger Response Format Interface
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
