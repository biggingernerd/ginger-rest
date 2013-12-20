<?php
/**
 * Ginger/Request/Url.php
 *
 * @author Martijn van Maasakkers
 */

namespace Ginger\Request;

/**
 * Ginger Request URL Handler
 */
class Url extends \Ginger\Url 
{

    /**
     * Read Current URL
     */
    public function __construct()
    {
        $url = "http" . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=="on") ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        parent::__construct($url);
    }
}
