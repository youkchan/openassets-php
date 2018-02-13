<?php
namespace youkchan\OpenassetsPHP\Tests\Networks;
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Networks\Monacoin;

class MonacoinTest extends TestCase
{

    public function setUp(){
        $this->monacoin = new Monacoin(); 
    }


   public function test_set() {
       var_dump($this->monacoin->set("max_confirmation",300));
       var_dump($this->monacoin->get("max_confirmation"));
   } 
}
