<?php
/**
 * XMLSerializer Object
 *
 * @author Big Ginger Nerd
 * @package be
 */

namespace Ginger;

/**
 * XMLSerializer handling class
 *
 * @author Big Ginger Nerd
 * @package be
 */
class XMLSerializer {

	/**
	 * Generate xml from object
	 * @param stdClass $obj
	 * @param string $node_block
	 * @param string $node_name
	 * @return string
	 */
	public static function generateValidXmlFromObj(stdClass $obj, $node_block='response', $node_name='item') {
        $arr = get_object_vars($obj);
        return self::generateValidXmlFromArray($arr, $node_block, $node_name);
    }

    /**
     * Generate valid xml from array
     * @param array $array
     * @param string $node_block
     * @param string $node_name
     * @return string
     */
    public static function generateValidXmlFromArray($array, $node_block='response', $node_name='item') {
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>';

        $xml .= '<' . $node_block . '>';
        $xml .= self::generateXmlFromArray($array, $node_name);
        $xml .= '</' . $node_block . '>';

        return $xml;
    }

    /**
     * Generate XML from array
     * 
     * @param array $array
     * @param string $node_name
     * @return string
     */
    private static function generateXmlFromArray($array, $node_name) {
        $xml = '';

        if (is_array($array) || is_object($array)) {
            foreach ($array as $key=>$value) {
                if (is_numeric($key)) {
                    $key = $node_name;
                }

                $xml .= '<' . $key . '>' . self::generateXmlFromArray($value, $node_name) . '</' . $key . '>';
            }
        } else {
            $xml = htmlspecialchars($array, ENT_QUOTES);
        }

        return $xml;
    }

}