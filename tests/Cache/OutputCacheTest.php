<?php
namespace youkchan\OpenassetsPHP\Tests\Cache;
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Cache\OutputCache;
use youkchan\OpenassetsPHP\Protocol\OutputType;

class OutputCacheTest extends TestCase
{

    public function setUp(){
        $this->output_cache = new OutputCache("../test.db");
        $this->transaction_id = "7ed86d1c2824ea14bf8a2fe27202a1d229a4f58db52e2ba1ed13cf36765deaac";
        $this->index = 0;
        $this->value = 100;
        $this->script = "OP_RETURN 4f41010001904e1b753d68747470733a2f2f6370722e736d2f35596753553150672d71";
        $this->asset_id = "AGHhobo7pVQN5fZWqv3rhdc324ryT7qVTB";
        $this->asset_quantity = 200;
        $this->output_type = OutputType::ISSUANCE;
        $this->metadata = "u=https://goo.gl/Q0NZfe";
    }

    public function test_get_set() {
        $result = $this->output_cache->get($this->transaction_id, $this->index);
        $this->assertEquals($result, false);
        $result = $this->output_cache->set($this->transaction_id, $this->index, $this->value, $this->script, $this->asset_id, $this->asset_quantity, $this->output_type, $this->metadata);
        $result = $this->output_cache->get($this->transaction_id, $this->index);
var_dump($result);
    }

    //public function test_set() {
        //var_dump($this->output_cache->set());
    //}

    /*public function test_setup() {
        var_dump($this->output_cache->get());
    }*/
}
