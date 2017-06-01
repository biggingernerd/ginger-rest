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
     * Read Current URL. If $url != '' we can use the given URL as parsable Url instead of $_SERVER generated.
     * This is needed for using encapsulation patterns or other bootstrapping mechanisms to overwrite the URL used.
     *
     * @param string $url
     */
    public function __construct($url = '')
    {
        if ($url === "") {
            $url = "http" . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        }

        parent::__construct($url);
    }
}
