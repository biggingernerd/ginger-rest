<?php
/**
 * Ginger/Request/Parameters.php
 *
 * @author Martijn van Maasakkers
 */

namespace Ginger\Request;

use \Ginger\Request\Url;
use \Ginger\Route;

/**
 * Ginger Request Parameters Handler
 */
class Parameters 
{

    /**
     * @var array $_allParameters All parameters
     */
    private $allParameters  = array();

    /**
     * @var array $_filterParameters Filter parameters (from Url)
     */
    private $filterParameters  = array();

    /**
     * @var array $_dataParameters All data parameters (from postfields object)
     */
    private $dataParameters  = array();

    /**
     * Reads parameters
     *
     * @param Url $url
     * @param Route $route
     */
    public function __construct(Url $url, Route $route)
    {
        $this->getParams($url->path, $route->route);
    }

    /**
     * Get all params
     * @param string $currentPath
     * @param string $route
     */
    private function getParams($currentPath, $route)
    {
        $path = substr($currentPath, strlen($route));
        $path = (substr($path, 0, 1) == "/") ? substr($path, 1): $path;
        $path = (substr($path, -1) == "/") ? substr($path, 0, -1): $path;

        $this->getFilterParams($path);
        $this->getDataParams();
        $this->parseParameterValues();
        $this->cleanReservedParams();
    }

    /**
     * Get all "filter" params from uri path
     *
     * @param string $path
     */
    private function getFilterParams($path)
    {
        if(!$path) {
            $path = "";
        }

        $parts = explode("/", $path);
        if($path == "") {

        } elseif(count($parts) == 1) {
            $this->filterParameters['id'] = $parts[0];
        } else {
            foreach($parts as $key => $part) {
                if(($key % 2) == 1) {
                    $this->filterParameters[$parts[$key-1]] = urldecode($part);
                } else {
                    $this->filterParameters[$part] = "";
                }
            }
        }

        $this->filterParameters = array_merge($_GET, $this->filterParameters);
    }

    /**
     * Loop through parameters and parse operators
     *
     * @param array $params
     *
     * @return array
     */
    private function parseOperators($params)
    {
        foreach($params as $key => $input) {
            if(substr($input, 0, 1) == "!") {
                $params[$key] = array("NOT" => $this->parseValue(substr($input, 1)));
            } elseif(substr($input, 0, 2) == "><") {
                $params[$key] = array("BETWEEN" => $this->parseValue(substr($input, 2)));
            } elseif(substr($input, 0, 2) == ">=") {
                $params[$key] = array("GTE" => $this->parseValue(substr($input, 2)));
            } elseif(substr($input, 0, 2) == "<=") {
                $params[$key] = array("LTE" => $this->parseValue(substr($input, 2)));
            } elseif(substr($input, 0, 1) == "<") {
                $params[$key] = array("LT" => $this->parseValue(substr($input, 1)));
            } elseif(substr($input, 0, 1) == ">") {
                $params[$key] = array("GT" => $this->parseValue(substr($input, 1)));
            } else {
                $params[$key] = $this->parseValue($input);
            }
        }

        return $params;
    }

    /**
     * Formats the value for internals
     *
     * @param string $input
     *
     * @return mixed
     */
    private function parseValue($input)
    {
        if(substr($input, 0, 1) == '"' && substr($input, -1) == '"') {
            $input = substr($input, 1, -1);
        } elseif(strpos($input, "|")) {
            $input = explode("|", $input);
            foreach($input as $key => $value) {
                $input[$key] = $this->parseValue($value);
            }
        } elseif($input == "false") {
            $input = false;
        } elseif($input == "true") {
            $input = true;
        } elseif(is_numeric($input)) {
            $input = (float)$input;
            if(!strpos($input, ".")) {
                $input = (int)$input;
            }
        } elseif($input == "on") {
            $input = true;
        }

        return $input;
    }

    /**
     * Loop through filter parameters and formats the values for internals
     */
    private function parseParameterValues()
    {
        $this->filterParameters = $this->parseOperators($this->filterParameters);
        foreach($this->dataParameters as $key => $input) {
            $this->dataParameters[$key] = $this->parseValue($input);
        }
    }

    /**
     * Parse postfield data when content-dispositino is happening, else return
     * normal postfield vars
     *
     * @return array
     */
    public function getPostFromInput() {
        $ct = $_SERVER['CONTENT_TYPE'];
        $position = stripos($ct, "multipart/form-data;");

        $input = file_get_contents("php://input");
        $postVars = array();
        if($position !== false) {

            $exp = explode("boundary=", $ct);
            if(isset($exp[1])) {
                $boundary = trim($exp[1]);
                $variables = explode("--".$boundary, $input);
                foreach($variables as $var) {
                    if(trim($var) != "" && trim($var) != "--") {
                        preg_match('/Content-Disposition: form-data; name="([^\"]*)"(.*)/si', $var, $result);
                        $postVars[trim($result[1])] = trim($result[2]);
                    }
                }
            }
        } else {
            parse_str($input, $postVars);
        }

        return $postVars;
    }

    /**
     * Get data params based on request method
     */
    public function getDataParams()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        switch($method)
        {
        case "POST":
            $this->dataParameters = $_POST;
            break;

        case "PUT":
        case "DELETE":
            $this->dataParameters = $this->getPostFromInput();
            break;
        }
    }

    /**
     * Read all reserved params and add them to class value
     */
    public function cleanReservedParams()
    {
        $params = array(
            "_format"        => "format",
            "_limit"         => "limit",
            "_offset"        => "offset",
            "_sort"          => "sort",
            "_direction"     => "direction",
            "_debug"         => "debug",
            "_options"       => "options",
            "_locale"        => "locale",
            "_mode"          => "mode",
            "_template"      => "template",
            "_flags"         => "flags",
            "_ts"            => "ts",
            "oauth_token"    => "oauth_token",
            "callback"       => "callback"
        );

        foreach($params as $param => $paramKey) {
            if(isset($this->filterParameters[$param])) {
                \Ginger\System\Parameters::$$paramKey = $this->filterParameters[$param];
                unset($this->filterParameters[$param]);
            }
        }

        if(isset($this->dataParameters["oauth_token"])) {
            \Ginger\System\Parameters::$oauth_token = $this->dataParameters["oauth_token"];
            unset($this->dataParameters["oauth_token"]);
        }

        // Make auth header leading
        if(isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $check_for = "oauth_token";
            if(substr($_SERVER['HTTP_AUTHORIZATION'], 0, strlen($check_for)) == $check_for) {
                \Ginger\System\Parameters::$oauth_token = trim(substr($_SERVER['HTTP_AUTHORIZATION'], strlen($check_for)));
            }
        }

        // Check for X-NI-API-Key
        if(isset($_SERVER['HTTP_X_API_KEY'])) {
            \Ginger\System\Parameters::$api_key = $_SERVER['HTTP_X_API_KEY'];
        }

        // Check for existing IP Address, but check if X-Original-IP isset
        if(isset($_SERVER['HTTP_X_ORIGINAL_IP'])) {
            \Ginger\System\Parameters::$ip = $_SERVER['HTTP_X_ORIGINAL_IP'];
        } else {
            \Ginger\System\Parameters::$ip = $_SERVER['REMOTE_ADDR'];
        }
        
        // Check for accept header and let it be leading
        if(isset($_SERVER['HTTP_ACCEPT'])) {
            \Ginger\System\Parameters::$format = \Ginger\Format::getFormatByAcceptHeader($_SERVER['HTTP_ACCEPT']);
        }
        $lang = "";
        if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        }
        
/*         $options = \Ginger\Options::getInstance(); */
        
        \Ginger\Options::getInstance()->locale = new \Ginger\Locale($lang);
    }

    /**
     * Return all filter parameters
     *
     * @return array
     */
    public function getFilterParameters()
    {
        return $this->filterParameters;
    }

    /**
     * Return all data parameters
     *
     * @return array
     */
    public function getDataParameters()
    {
        return $this->dataParameters;
    }
}
