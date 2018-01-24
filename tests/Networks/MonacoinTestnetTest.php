<?php
namespace youkchan\OpenassetsPHP\Tests\Networks;
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Networks\MonacoinTestnet;

class MonacoinTestnetTest extends TestCase
{

    public function setUp(){
        $this->monacoinTestnet = new MonacoinTestnet(); 
    }


   public function test_set() {
       var_dump($this->monacoinTestnet->set("max_confirmation",300));
       var_dump($this->monacoinTestnet->get("max_confirmation"));
   } 
}
