<?php
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Openassets;

class OpenassetsTestnetTest extends TestCase
{
    public function testGetApi(){
        $openassets = new Openassets();
        $api = $openassets
               ->setNetwork('testnet')
               ->getApi();
    }
}
