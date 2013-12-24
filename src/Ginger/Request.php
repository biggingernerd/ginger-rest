<?php
/**
 * Ginger/Request.php
 *
 * @author Martijn van Maasakkers
 */

namespace Ginger;

use \Ginger\Request\Parameters;
use \Ginger\Request\Url;
use \Ginger\Response;

/**
 * Ginger Request Handler
 *
 */
class Request 
{

    /**
     * Contains the parameters object
     *
     * @var \Ginger\Request\Parameters
     */
    private $parameters;

    /**
     * Contains the current URL object
     *
     * @var \Ginger\Request\Url
     */
    private $url;

    /**
     * @var \Ginger\Request\Route $route Route object
     */
    private $route;

    /**
     * @var string $method Request Method
     */
    private $method;

    /**
     * @var string $action Action
     */
    private $action;
    /**
     * access
     *
     * (default value: false)
     *
     * @var bool
     * @access private
     */
    private $access = false;

    /**
     * data
     *
     * (default value: false)
     *
     * @var bool
     * @access private
     */
    private $data = false;

    /**
     * response
     *
     * (default value: null)
     *
     * @var mixed
     * @access private
     */
    private $response = null;

    /**
     * Constructor function
     */
    public function __construct()
    {
        $this->url          = new Url();
        $this->route        = \Ginger\Routes::detect($this->getUrl()->path);
        $this->parameters   = new Parameters($this->url, $this->route);
        $this->action       = $this->getAction();

        if($this->action == "options") {
            \Ginger\System\Parameters::$template = $this->action;    
        } else {
            if(!\Ginger\System\Parameters::$template) {
                \Ginger\System\Parameters::$template = $this->route->route."/".$this->action;    
            }
        }

        $this->response = new Response();
        $this->response->setRequest($this);
    }

    /**
     * Load file and dispatch to response
     */
    public function go()
    {
        if($this->action == "options") {
            $file = "options".$this->getExtension();
        } else {
            // Check if handler file exists
            if($this->route->route == "/") {
                $file = $this->getAction().$this->getExtension();
            } else {
                $file = $this->route->resource . "/" .$this->getAction().$this->getExtension();
            }
        }

        $fullFilePath = stream_resolve_include_path($file);
        if($fullFilePath) {
            include($fullFilePath);
        } else {
            throw new \Ginger\Exception("Not implemented", 501);
        }

        $this->getResponse()->send();
    }

    /**
     * Return parameter object
     *
     * @return \Ginger\Request\Parameters
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Return the filter array
     *
     * @return array
     */
    public function getFilterParameters()
    {
        return $this->parameters->getFilterParameters();
    }

    /**
     * Return the data array
     *
     * @return array
     */
    public function getDataParameters()
    {
        return $this->parameters->getDataParameters();
    }

    /**
     * Return Route object
     *
     * @return \Ginger\Request\Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Return URL object
     *
     * @return \Ginger\Request\Url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Return Request Method
     *
     * @return string
     */
    public function getMethod()
    {
        if(!isset($this->method)) {
            $this->method = $_SERVER['REQUEST_METHOD'];
        }

        return $this->method;
    }

    /**
     * Return current action
     *
     * @return string
     */
    public function getAction()
    {
        if($this->action) {
            return $this->action;
        } else {
            $action = "index";
            switch($_SERVER['REQUEST_METHOD']) {
            case "GET":
                if(count($this->getFilterParameters()) == 0) {
                    $action = "index";
                } else {
                    $action = "get";
                }
                break;
            case "POST":
                $action = "post";
                break;
            case "PUT":
                $action = "put";
                break;
            case "DELETE":
                $action = "delete";
                break;
            case "HEAD":
                $action = "head";
                break;
            case "OPTIONS":
                $action = "options";
                break;
            }

            return $action;
        }
    }

    /**
     * Return module file extension
     *
     * @return string
     */
    public function getExtension()
    {
        return ".php";
    }

    /**
     * setResponseData function.
     *
     * @access public
     * @param array $data (default: array())
     * @return void
     */
    public function setResponseData($data = array())
    {
        $this->data = $data;
    }

    /**
     * getResponseData function.
     *
     * @access public
     * @return void
     */
    public function getResponseData()
    {
        return $this->data;
    }

    /**
     * setTemplate function.
     * 
     * @access public
     * @param string $template
     * @return void
     */
    public function setTemplate($template) {
        \Ginger\System\Parameters::$template = $template;
    }

    /**
     * getTemplate function.
     * 
     * @access public
     * @return void
     */
    public function getTemplate() {
        return \Ginger\System\Parameters::$template;
    }

    /**
     * getResponse function.
     *
     * @access public
     * @return void
     */
    public function getResponse()
    {
        return $this->response;
    }
}
