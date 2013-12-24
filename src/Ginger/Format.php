<?php
/**
 * Ginger/Format.php
 *
 * @author Martijn van Maasakkers
 * @todo Actually formatting the error message here would be better
 */

namespace Ginger;

/**
 * Ginger Format Handler
 */
class Format
{
    /**
     * format
     * 
     * (default value: "json")
     * 
     * @var string
     * @access public
     * @static
     */
    public static $format = "json";
    /**
     * mimetype
     * 
     * (default value: "application/json")
     * 
     * @var string
     * @access public
     * @static
     */
    public static $mimetype = "application/json";
    
    /**
     * allowedFormats
     * 
     * @var mixed
     * @access private
     * @static
     */
    private static $allowedFormats = array("json" => array(
            "class" => "Json",
            "mimetype" => "application/json"
        ),
        "jsonp" => array(
            "class" => "Jsonp",
            "mimetype" => "application/json"
        ),
        "xml" => array(
            "class" => "Xml",
            "mimetype" => "application/xml"
        ),
        "phps" => array(
            "class" => "Phps",
            "mimetype" => "text/plain"
        ),
        "html" => array(
            "class" => "Html",
            "mimetype" => "text/html"
        )
    );
    
    /**
     * mimeMapper
     * 
     * @var mixed
     * @access private
     * @static
     */
    private static $mimeMapper = array(
        "application/json"  => "json",
        "application/jsonp" => "jsonp",
        "application/xml"   => "xml",
        "application/phps"  => "phps",
        "text/html"         => "html"
    );

    /**
     * getFormatByAcceptHeader function.
     * 
     * @access public
     * @static
     * @param mixed $header
     * @return void
     */
    public static function getFormatByAcceptHeader($header)
    {
        if(isset(self::$mimeMapper[$header])) {
            return self::$mimeMapper[$header];
        } else {
            return self::$format;
        }
    }
}
