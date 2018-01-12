<?php
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Provider;
use youkchan\OpenassetsPHP\Network;
use youkchan\OpenassetsPHP\Openassets;


class ProviderTest extends TestCase
{

/*
    public function testPost(){
        $provider = new Provider();
        $result = $provider->post();
    }
*/
    public function setUp(){
        $this->network = new Network(); 
        $this->provider = new Provider($this->network);
    }

    public function test_list_unspent(){
        $this->provider->list_unspent([],$this->network);
    }
}
