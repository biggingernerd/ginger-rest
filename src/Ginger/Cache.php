<?php

namespace Ginger;

/**
 * Cache class.
 */
class Cache
{

    public function get($key)
    {
    
    }

    public function set($key, $value)
    {
    
    }
    
    public function exists($key)
    {
    
    }
    
    public function delete($key)
    {
    
    }
    
    protected static $instance = null;

    protected function __construct()
    {
    
    }
    
    protected function __clone()
    {
    
    }
    
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }
        return static::$instance;
    }
}
