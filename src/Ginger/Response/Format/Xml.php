<?php
/**
 * Ginger/Response/Format/Xml.php
 *
 * @author Martijn van Maasakkers
 */

namespace Ginger\Response\Format;

/**
 * Ginger Response Format Xml formatter
 */
class Xml implements Format 
{

    /**
     * Return string representation of $data
     *
     * @param mixed $data
     * @return string
     */
    public static function Parse($data)
    {
        return \Ginger\XMLSerializer::generateValidXmlFromArray($data);
    }
}
