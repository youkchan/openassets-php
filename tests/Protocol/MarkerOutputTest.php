<?php
namespace youkchan\OpenassetsPHP\Tests\Protocol;
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Protocol\MarkerOutput;

class MarkerOutputTest extends TestCase
{

    public function test_deserialize_payload(){

        //MarkerOutput::deserialize_payload("4f41010001641668747470733a2f2f686f6765686f67652e636f2e6a70");
        MarkerOutput::deserialize_payload("4f41010003ac0200e58e260412345678");

    }

}
