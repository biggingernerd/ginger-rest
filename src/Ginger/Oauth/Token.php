<?php
/**
 * Ginger/Oauth/Token.php
 * 
 * @author Big Ginger Nerd
 * @package Ginger
 */

namespace Ginger\Oauth;

use \Documents\Oauth\Token as DOT;
use \Documents\Oauth\Tokens as DOTS;
use \Documents\Profile\User;

/**
 * Token class.
 */
class Token {

	/**
	 * _token
	 * 
	 * @var mixed
	 * @access private
	 * @static
	 */
	private static $_token;
	/**
	 * _user
	 * 
	 * @var mixed
	 * @access private
	 * @static
	 */
	private static $_user;
	
	/**
	 * isValid function.
	 * 
	 * @access public
	 * @static
	 * @param bool $token (default: false)
	 * @return void
	 */
	public static function isValid($token = false)
	{
		$dot = new DOTS(array("token" => $token, "used" => false));
		self::$_token = $token;
		if($dot->total == 1)
		{
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * getUser function.
	 * 
	 * @access public
	 * @static
	 * @param bool $token (default: false)
	 * @param bool $exception (default: false)
	 * @return void
	 */
	public static function getUser($token = false, $exception = false)
	{
		if($token)
		{
			self::$_token = $token;
		} elseif(isset(self::$_token))
		{
			$token = self::$_token;
		}	
		
		$dot = new DOTS(array("token" => $token, "used" => false));
		
		if($dot->total == 1)
		{
			$token = $dot->items[0];
			
			$user = new User($token->profile);
			
			return $user;
		} else {
			if($exception)
			{
				throw new \Exception("Access denied", 401);
			}			
		
			return false;
		}
	}
	
}