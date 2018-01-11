<?php
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Openassets;

class OpenassetsTest extends TestCase
{
    public function testGetApi(){
        $openassets = new Openassets();
        $api = $openassets->getApi();
        $this->assertSame($api->get("network"),'mainnet');
        $this->assertSame($api->get("provider"),'monacoin');
        $this->assertSame($api->get("cache"),'cache.db');
        $this->assertSame($api->get("dust_limit"),600);
        $this->assertSame($api->get("default_fee"),10000);
        $this->assertSame($api->get("min_confirmation"),1);
        $this->assertSame($api->get("max_confirmation"),9999999);
        $this->assertSame($api->get("rpc_host"),'localhost');
        $this->assertSame($api->get("rpc_user"),'');
        $this->assertSame($api->get("rpc_password"),'');
        $this->assertSame($api->get("rpc_wallet"),'');
        $this->assertSame($api->get("rpc_schema"),'https');
        $this->assertSame($api->get("rpc_timeout"),60);
        $this->assertSame($api->get("rpc_open_timeout"),60);
    }
}
