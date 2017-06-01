<?php
/**
 * Ginger/Exception.php
 *
 * @author Martijn van Maasakkers
 * @todo Actually formatting the error message here would be better
 */

namespace Ginger;

/**
 * Ginger Exception Handler
 */
class Exception extends \Exception
{
    /**
     * Sends error message back to the requesting user.
     *
     * @access public
     * @param string $message
     * @param int $code
     * @return void
     */
    public function __construct($message, $code)
    {
        $this->message = $message;
        $this->code = $code;

        /**
         * mvmaasakkers: We need the thrown exceptions above. This monstrosity of "Let's output the exception content
         * directly" has to go... I've commented it out during WIP.
         */
//        $request = new Request();
//        $response = $request->getResponse();
//        $response->setStatus($code);
//        $response->setData(array("error" => $message));
//        $response->send();
    }
}
