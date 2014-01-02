<?php
/**
 * Ginger/Locale.php
 *
 * @author Martijn van Maasakkers
 */

namespace Ginger;

/**
 * Locale class.
 */
class Locale 
{
    /**
     * language
     * 
     * (default value: "nl")
     * 
     * @var string
     * @access public
     */
    public $language = "nl";
    /**
     * region
     * 
     * (default value: "NL")
     * 
     * @var string
     * @access public
     */
    public $region = "NL";
    
    /**
     * locale
     * 
     * (default value: "nl_NL")
     * 
     * @var string
     * @access public
     */
    public $locale = "nl_NL";
    
    /**
     * __construct function.
     * 
     * @access public
     * @param string $locale (default: "nl_NL")
     * @return void
     */
    public function __construct($locale = "nl_NL")
    {
        if($locale == "") {
            $locale = "nl_NL";
        }
    
        if(strpos($locale, ",") !== false) {
            $aaparts = explode(",", $locale);
            $locale = $aaparts[0];
        }

        $locale = str_replace(array("-"), "_", $locale);
        $parts = explode("_", $locale);
        
        $this->language = strtolower($parts[0]);
        $this->region = strtoupper($parts[1]);
        
        $this->locale = $this->language . "_" . $this->region;
        
        setlocale(LC_COLLATE,   $this->locale);
        setlocale(LC_CTYPE,     $this->locale);
        setlocale(LC_MONETARY,  $this->locale);
        setlocale(LC_TIME,      $this->locale);
        setlocale(LC_MESSAGES,  $this->locale);
    }
}
