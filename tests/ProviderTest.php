<?php
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Provider;

class ProviderTest extends TestCase
{
    public function testPost(){
        $provider = new Provider();
        $result = $provider->post();
    }
}
