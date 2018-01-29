<?php
namespace youkchan\OpenassetsPHP\Tests\Cache;
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Cache\OutputCache;
use youkchan\OpenassetsPHP\Protocol\OutputType;

class OutputCacheTest extends TestCase
{

    public function setUp(){
        $path = "../test.db";
        if(file_exists($path)) {
            unlink($path);
        }
        $this->output_cache = new OutputCache($path);
        $this->transaction_id = "7ed86d1c2824ea14bf8a2fe27202a1d229a4f58db52e2ba1ed13cf36765deaac";
        $this->index = 0;
        $this->value = 100;
        $this->script = "76a914cb5cde5d340d498c3be4533891173ec052878ab788ac";
        $this->asset_id = "AGHhobo7pVQN5fZWqv3rhdc324ryT7qVTB";
        $this->asset_quantity = 200;
        $this->output_type = OutputType::ISSUANCE;
        $this->metadata = "u=https://goo.gl/Q0NZfe";
    }

    public function test_get_set() {
        $result = $this->output_cache->get($this->transaction_id, $this->index);
        $this->assertSame($result, null);
        $this->output_cache->set($this->transaction_id, $this->index, $this->value, $this->script, $this->asset_id, $this->asset_quantity, $this->output_type, $this->metadata);
        $result = $this->output_cache->get($this->transaction_id, $this->index);
        $this->assertEquals($result->value, $this->value);
        $this->assertEquals($result->script->getHex(), $this->script);
        $this->assertEquals($result->asset_id, $this->asset_id);
        $this->assertEquals($result->asset_quantity, $this->asset_quantity);
        $this->assertEquals($result->output_type, $this->output_type);
        $this->assertEquals($result->metadata, $this->metadata);
    }

}
