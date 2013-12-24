<?php

class GingerRequestUrlTest extends \PHPUnit_Framework_TestCase
{
    public $testUrl = "https://username:password@www.example.co.uk/a/few/folders/with?query=parameters&and=more#22";

    public function testUrl()
    {
        $url = new \Ginger\Url($this->testUrl);
        $this->assertEquals("https", $url->scheme);
        $this->assertEquals("www.example.co.uk", $url->host);
    }
    
    public function testUsernamePassword()
    {
        $url = new \Ginger\Url($this->testUrl);
        $this->assertEquals("username", $url->user);
        $this->assertEquals("password", $url->pass);
    }
    
    public function testQueryParameters()
    {
        $url = new \Ginger\Url($this->testUrl);
        $this->assertEquals("parameters", $url->queryParts['query']);
        $this->assertEquals("more", $url->queryParts['and']);
    }
    
    public function testFragment()
    {
        $url = new \Ginger\Url($this->testUrl);
        $this->assertEquals("22", $url->fragment);
    }
}
