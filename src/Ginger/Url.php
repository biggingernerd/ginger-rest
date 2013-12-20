<?php
/**
 * Ginger/Url.php
 *
 * @author Martijn van Maasakkers
 */

namespace Ginger;

/**
 * Ginger URL
 */
class Url 
{

    /**
     * scheme
     *
     * Used scheme (usually http or https)
     *
     * @var string
     * @access public
     */
    public $scheme;

    /**
     * host
     *
     * Host
     *
     * @var string
     * @access public
     */
    public $host;

    /**
     * path
     *
     * URL Path
     *
     * @var string
     * @access public
     */
    public $path;

    /**
     * query
     *
     * Raw query
     *
     * @var string
     * @access public
     */
    public $query;
    
    /**
     * user
     * 
     * @var string
     * @access public
     */
    public $user;
    
    /**
     * pass
     * 
     * @var string
     * @access public
     */
    public $pass;
    

    /**
     * queryParts
     *
     * All queryparts based in key=value
     *
     * @var array
     * @access public
     */
    public $queryParts = array();
    
    /**
     * fragment
     * 
     * @var string
     * @access public
     */
    public $fragment;

    /**
     * Parse url into object
     *
     * @param string $url URL String
     */
    public function __construct($url)
    {
        $url = parse_url($url);
        
        $this->scheme  = $url['scheme'];
        $this->host  = $url['host'];
        $this->path  = $url['path'];
        $this->query = (isset($url['query'])) ? $url['query'] : "";

        if(isset($url['user'])) {
            $this->user = $url['user'];
        }

        if(isset($url['pass'])) {
            $this->pass = $url['pass'];
        }
        
        if(isset($url['fragment'])) {
            $this->fragment = $url['fragment'];
        }
        
        parse_str($this->query, $this->queryParts);
    }

    /**
     * getParameter function.
     *
     * @access public
     * @param mixed $key
     * @return void
     */
    public function getParameter($key)
    {
        if(isset($this->queryParts[$key])) {
            return $this->queryParts[$key];
        } else {
            return false;
        }
    }
}
