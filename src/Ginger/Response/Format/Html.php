<?php
/**
 * Ginger/Response/Format/Json.php
 * 
 * @author Martijn van Maasakkers
 */
 
namespace Ginger\Response\Format;

/**
 * Ginger Response Format JSON formatter
 */
class Html implements Format {
	
	/**
	 * Return string representation of $data
	 * 
	 * @param mixed $data
	 * @return string
	 */
	public static function Parse($data)
	{
	    $filepath = BASEPATH."/templates/".\Ginger\System\Parameters::$template.".phtml";

        if(file_exists($filepath)) {
            ob_start();
            include($filepath);
            $output = ob_get_contents();
            ob_end_clean();

            return $output;
	    } elseif(\Ginger\System\Parameters::$template != "options") {
    	    $config = array(
                   "tpl_dir"       => GINGER_TEMPLATE_PATH,
                   "cache_dir"     => GINGER_TEMPLATE_CACHE_PATH
            );
    
            \Rain\Tpl::configure( $config );
    	
            $data = json_decode(json_encode($data), true);
    
            $t = new \Rain\Tpl;
            $t->assign($data);
            $output = $t->draw(\Ginger\System\Parameters::$template, true);
    
    		return $output;
    		
		} else {
    		return "";
		}
	}	
}
