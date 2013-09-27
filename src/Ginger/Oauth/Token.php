<?php

namespace Ginger\Oauth;

use \Documents\Oauth\Token as DOT;
use \Documents\Oauth\Tokens as DOTS;
use \Documents\Profile\User;

class Token {

	private static $_token;
	private static $_user;
	
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