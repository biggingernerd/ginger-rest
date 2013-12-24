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
    public static $format = "json";
    public static $mimetype = "application/json";
    
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
    
    private static $mimeMapper = array(
        "application/json"  => "json",
        "application/jsonp" => "jsonp",
        "application/xml"   => "xml",
        "application/phps"  => "phps",
        "text/html"         => "html"
    );

    public static function getFormatByAcceptHeader($header)
    {
        if(isset(self::$mimeMapper[$header])) {
            return self::$mimeMapper[$header];
        } else {
            return self::$format;
        }
    }
}
