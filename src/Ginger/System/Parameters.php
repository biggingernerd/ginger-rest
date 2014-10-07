<?php
/**
 * Ginger/System/Parameters.php
 *
 * @author Martijn van Maasakkers
 */

namespace Ginger\System;


/**
 * Ginger System Parameters Handler
 */
class Parameters {

    /**
     * @var string $format Format value (default "json")
     */
    public static $format = "json";

    /**
     * @var int $limit Limit value (default "10")
     */
    public static $limit = 10;

    /**
     * @var int $offset Offset value (default "0")
     */
    public static $offset = 0;

    /**
     * @var string $sort Sort value (default "created")
     */
    public static $sort = "created";

    /**
     * @var string $direction Sort Direction value (default "asc")
     */
    public static $direction = "asc";

    /**
     * @var string $debug Debug value (default "false")
     */
    public static $debug = false;

    /**
     * @var string $callback Callback value for jsonp (default "null")
     */
    public static $callback;

    /**
     * @var string $template Template file for html parser (default "null")
     */
    public static $template;

    /**
     * @var string $locale Locale value (default "null")
     */
    public static $locale;

    /**
     * @var string $oauth_token Oauth token (default "null")
     */
    public static $oauth_token;

    public static $bearer_token;
    /**
     * api_key
     *
     * @var mixed
     * @access public
     * @static
     */
    public static $api_key;

    /**
     * flags
     *
     * @var mixed
     * @access public
     * @static
     */
    public static $flags;

    /**
     * mode
     *
     * @var mixed
     * @access public
     * @static
     */
    public static $mode;

    /**
     * options
     *
     * @var mixed
     * @access public
     * @static
     */
    public static $options;

    /**
     * ip
     *
     * @var mixed
     * @access public
     * @static
     */
    public static $ip;

    /**
     * ts
     *
     * @var mixed
     * @access public
     * @static
     */
    public static $ts;
}
