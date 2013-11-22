<?php
/**
 * Ginger/Response.php
 *
 * @author Big Ginger Nerd
 * @package Ginger
 */

namespace Ginger;

/**
 * Ginger Response Handler
 *
 * @package Ginger\Library
 */
class Response 
{

    /**
     * Format value
     * @var string
     */
    private $format;

    /**
     * filters
     *
     * @var mixed
     * @access private
     */
    private $filters;

    /**
     * action
     *
     * @var mixed
     * @access private
     */
    private $action;

    /**
     * request
     *
     * @var mixed
     * @access private
     */
    private $request;

    /**
     * All allowed formats + classes and mimetypes
     * @var array
     */
    private $allowedFormats = array("json" => array(
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
     * Default format value
     * @var string
     */
    private $defaultFormat = "json";

    /**
     * data
     *
     * (default value: null)
     *
     * @var mixed
     * @access private
     */
    private $data = array("response" => "");

    /**
     * status
     *
     * (default value: 200)
     *
     * @var int
     * @access private
     */
    private $status = 200;

    /**
     * callback
     *
     * (default value: null)
     *
     * @var mixed
     * @access private
     */
    private $callback = null;

    /**
     * headers
     *
     * Contains the custom response headers
     *
     *
     * @var array
     * @access private
     */
    private static $headers = array();

    /**
     * Constructor. Sets default values and sends data.
     * @param \Ginger\Request $request
     */
    public function __construct()
    {
        $this->setCallback();
        $this->setFormat();
    }

    /**
     * Passes data to one of the format interfaces and returns string value
     *
     * @param string $data
     */
    private function parseDataToString($data)
    {
        $format = $this->allowedFormats[$this->format];
        $className = "\\Ginger\\Response\\Format\\".$format['class'];
        return $className::Parse($data);

    }

    /**
     * Sets status header, content-type header and outputs data.
     * Die is there for making sure nothing else is processed after this.
     */
    public function send()
    {
        $data = $this->parseDataToString($this->data);
        $format = $this->allowedFormats[$this->format];

        // Send status header
        header($this->getStatus(), true, $this->getStatus());

        foreach(self::$headers as $key => $value) {
            header($key.": ".$value);
        }

        // Send content type header
        header("Content-Type: ".$format['mimetype']."; charset=utf-8");



        if(function_exists("ginger_log")) {
            ginger_log($this);
        }

        echo $data;

        die();
    }

    /**
     * setFormat function.
     *
     * @access public
     * @param bool $format (default: false)
     * @return void
     */
    public function setFormat($format = false)
    {
        if(!$format)
        {
            $format = \Ginger\System\Parameters::$format;
        }
        $this->format   = $format;

        if(!isset($this->allowedFormats[$this->format]))
        {
            $this->format = $this->defaultFormat;
        }
    }

    /**
     * getFormat function.
     *
     * @access public
     * @return void
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * setData function.
     *
     * @access public
     * @param mixed $data
     * @return void
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * getData function.
     *
     * @access public
     * @return void
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * setStatus function.
     *
     * @access public
     * @param mixed $status
     * @return void
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * getStatus function.
     *
     * @access public
     * @return void
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * setCallback function.
     *
     * @access public
     * @param bool $callback (default: false)
     * @return void
     */
    public function setCallback($callback = false)
    {
        if(!$callback)
        {
            $callback = \Ginger\System\Parameters::$callback;
        }
        $this->callback = $callback;
    }

    
    /**
     * getFilters function.
     * 
     * @access public
     * @return void
     */
    public function getFilters()
    {
        return $this->request->getFilterParameters();
    }

    /**
     * getAction function.
     * 
     * @access public
     * @return void
     */
    public function getAction()
    {
        return $this->request->getAction();
    }

    /**
     * setRequest function.
     * 
     * @access public
     * @param mixed $request
     * @return void
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * getRequest function.
     * 
     * @access public
     * @return void
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * addHeader function.
     * 
     * @access public
     * @static
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public static function addHeader($key, $value)
    {
        self::$headers[$key] = $value;
    }

    /**
     * removeHeader function.
     * 
     * @access public
     * @static
     * @param mixed $key
     * @return void
     */
    public static function removeHeader($key)
    {
        if(isset(self::$headers[$key])) {
            unset(self::$headers[$key]);
        }
    }

    /**
     * getHeaders function.
     * 
     * @access public
     * @static
     * @return void
     */
    public static function getHeaders()
    {
        return self::$headers;
    }

    /**
     * getCallback function.
     *
     * @access public
     * @return void
     */
    public function getCallback()
    {
        return $this->callback;
    }
}
