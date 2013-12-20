<?php

include(__DIR__."/../src/Ginger/Route.php");

class GingerRouteTest extends \PHPUnit_Framework_TestCase
{
    public function testRoute()
    {
        $route = new \Ginger\Route("simple/route/");
        $this->assertEquals("/simple/route", $route->route);
    }
    
    public function testResource()
    {
        $route = new \Ginger\Route("simple/route/");
        $this->assertEquals("simple/route", $route->resource);
    }
    
    public function testPath()
    {
        $route = new \Ginger\Route("simple/route/");
        $this->assertEquals("/simple/route/", $route->path);
    }
}