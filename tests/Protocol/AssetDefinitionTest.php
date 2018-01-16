<?php
namespace youkchan\OpenassetsPHP\Tests\Protocol;
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Protocol\AssetDefinition;

class AssetDefinitionTest extends TestCase
{

       private $json = '{"asset_ids":["AGHhobo7pVQN5fZWqv3rhdc324ryT7qVTB","AWo3R89p5REmoSyMWB8AeUmud8456bRxZL","AJk2Gx5V67S2wNuwTK5hef3TpHunfbjcmX"],"version":"1.0","divisibility":1,"name_short":"YouCoin","name":"MYou Coin","contract_url":"http://hogehoge.com/","issuer":"youkchan","description":"The OpenAsset test description.","description_mime":"text/x-markdown; charset=UTF-8","type":"Currency","link_to_website":false}';


   public function test_parse_json() {
        $asset_definition = AssetDefinition::parse_json($this->json);
        $this->assertEquals(3, count($asset_definition->asset_ids));
        $this->assertEquals('AGHhobo7pVQN5fZWqv3rhdc324ryT7qVTB', $asset_definition->asset_ids[0]);
        $this->assertEquals('YouCoin', $asset_definition->name_short);
        $this->assertEquals('MYou Coin', $asset_definition->name);
        $this->assertEquals('http://hogehoge.com/', $asset_definition->contract_url);
        $this->assertEquals('youkchan', $asset_definition->issuer);
        $this->assertEquals('The OpenAsset test description.', $asset_definition->description);
        $this->assertEquals('text/x-markdown; charset=UTF-8', $asset_definition->description_mime);
        $this->assertEquals('Currency', $asset_definition->type);
        $this->assertEquals(1, $asset_definition->divisibility);
        $this->assertFalse($asset_definition->link_to_website);
        $this->assertNull($asset_definition->icon_url);
        $this->assertNull($asset_definition->image_url);
        $this->assertEquals('1.0', $asset_definition->version);
   } 
}
