<?php

class GingerRequestParametersTest extends \PHPUnit_Framework_TestCase
{
    public $testUrl = "https://www.example.co.uk/simple/route/asstring/sdfoaijsn/asint/1234/asfloat/123.333/asboolean/true/asarray/a|b|c|d";
    public $testRoute = "simple/route/";
    
    public function fuglyFix()
    {
        $_SERVER['HTTP_HOST'] = "www.example.co.uk";
        $_SERVER['HTTPS'] = "on";
        $_SERVER['REQUEST_URI'] = "/simple/route/asstring/my+name+is/asint/1234/asfloat/123.333/asboolean/true/asarray/a|b|c|d";
        $_SERVER['REQUEST_METHOD'] = "GET";
        $_SERVER['REMOTE_ADDR'] = "127.0.0.1";
    }
    
    public function testParameters()
    {
        $this->fuglyFix();
    
        $url = new \Ginger\Request\Url($this->testUrl);
        $route = new \Ginger\Route($this->testRoute);
    
        $parameters = new \Ginger\Request\Parameters($url, $route);
    }
    
    public function testFilterParameters()
    {
        $this->fuglyFix();
        
        $url = new \Ginger\Request\Url($this->testUrl);
        $route = new \Ginger\Route($this->testRoute);
    
        $parameters = new \Ginger\Request\Parameters($url, $route);
        $filter = $parameters->getFilterParameters();
        
        $this->assertEquals("my name is", $filter['asstring']);
        $this->assertEquals(1234, $filter['asint']);
        $this->assertEquals(123.333, $filter['asfloat']);
        $this->assertEquals(true, $filter['asboolean']);
        $this->assertArrayHasKey(2, $filter['asarray']);
        $this->assertEquals('c', $filter['asarray'][2]);
    }
}
