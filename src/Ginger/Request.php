<?php
/**
 * Ginger/Request.php
 *
 * @author Martijn van Maasakkers
 */

namespace Ginger;

use \Ginger\Response;
use \Ginger\Url;
use \Ginger\Request\Parameters;

/**
 * Ginger Request Handler
 *
 */
class Request
{

    /**
     * Contains the parameters object
     *
     * @var Parameters
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
     * @var \Ginger\Response
     * @access private
     */
    private $response;

    /**
     * Constructor function
     *
     * If $skip = true (default it is false) we can skip the initial initialization and use the setter methods later on.
     *
     * @param bool $skip
     */
    public function __construct($skip = false)
    {
        if(!$skip) {
            $this->url = new \Ginger\Request\Url();
            $this->route = \Ginger\Routes::detect($this->getUrl()->path);
            $this->parameters = new Parameters($this->url, $this->route);
            $this->action = $this->getAction();

            if ($this->action == "options") {
                \Ginger\System\Parameters::$template = $this->action;
            } else {
                if (!\Ginger\System\Parameters::$template) {
                    \Ginger\System\Parameters::$template = $this->route->{"resource"} . "/" . $this->action;
                }
            }

            $this->response = new Response();
            $this->response->setRequest($this);
        }
    }

    /**
     * The setRoute is needed so we can directly attach/overwrite routes even after the Request class is instantiated.
     *
     * @param Route $route
     * @return void
     */
    public function setRoute(\Ginger\Route $route)
    {
        $this->route = $route;
    }

    /**
     * The setParameters is needed so we can directly attach/overwrite parameters even after the Request class is
     * instantiated
     *
     * @param Parameters $parameters
     * @return void
     */
    public function setParameters(Parameters $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * The setAction is needed so we can directly attach/overwrite action even after the Request class is
     * instantiated
     *
     * @param string $action
     * @return void
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * The setUrl is needed so we can directly attach/overwrite url even after the Request class is
     * instantiated
     *
     * @param Response $response
     * @return void
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * The setResponse is needed so we can directly attach/overwrite response object even after the Request class is
     * instantiated
     *
     * @param Url $url
     * @return void
     */
    public function setURL(Url $url)
    {
        $this->url = $url;
    }

    /**
     * Load file and dispatch to response. $return = false means default action (which is to output directly).
     * $return = true means return the response object. This way we can use this in bootstrapping or encapsulation.
     *
     * @param bool $return
     * @return \Ginger\Response
     * @throws Exception
     */
    public function go($return = false)
    {
        if ($this->action == "options") {
            $file = "options" . $this->getExtension();
        } else {
            // Check if handler file exists
            if ($this->route->route == "/") {
                $file = $this->getAction() . $this->getExtension();
            } else {
                $file = $this->route->resource . "/" . $this->getAction() . $this->getExtension();
            }
        }
        $fullFilePath = stream_resolve_include_path($file);
        if ($fullFilePath) {
            include $fullFilePath;
        } else {
            throw new \Ginger\Exception("Not implemented", 501);
        }

        if (!$return) {
            // This getResponse()->send() outputs directly and exits.
            $this->getResponse()->send();
        } else {
            return $this->getResponse();
        }
    }

    /**
     * Return parameter object
     *
     * @return Parameters
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
     * Return the filter array
     *
     * @return array
     */
    public function getPostBody()
    {
        return $this->parameters->getPostBody();
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
        if (!isset($this->method)) {
            $this->method = $_SERVER['REQUEST_METHOD'];
        }

        return $this->method;
    }

    /**
     * Return current action. This function is made to be backwards compatible so if $method is empty we fall
     * back in old methods (using $_SERVER['REQUEST_METHOD'] and using cached version of $action in memory.
     *
     * @param string $method
     * @return string
     */
    public function getAction($method = '')
    {
        if (empty($method)) {
            if ($this->action != "") {
                return $this->action;
            }
            $method = $_SERVER['REQUEST_METHOD'];
        }
        $action = "index";
        switch ($method) {
            case "GET":
                if (count($this->getFilterParameters()) == 0) {
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
            case "SEARCH":
                $action = "search";
                break;
            case "OPTIONS":
                $action = "options";
                break;
        }
        $this->action = $action;
        return $action;
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
    public function setTemplate($template)
    {
        \Ginger\System\Parameters::$template = $template;
    }

    /**
     * getTemplate function.
     *
     * @access public
     * @return void
     */
    public function getTemplate()
    {
        return \Ginger\System\Parameters::$template;
    }

    /**
     * getResponse function.
     *
     * @access public
     * @return \Ginger\Response
     */
    public function getResponse()
    {
        return $this->response;
    }
}
