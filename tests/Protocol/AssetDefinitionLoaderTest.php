<?php
namespace youkchan\OpenassetsPHP\Tests\Protocol;
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Protocol\AssetDefinitionLoader;
use youkchan\OpenassetsPHP\Protocol\HttpAssetDefinitionLoader;

class AssetDefinitionLoaderTest extends TestCase
{

    public function test_http_load()
    {
        $asset_definition_loader = new AssetDefinitionLoader('http://goo.gl/fS4mEj');
        $this->assertInstanceOf(HttpAssetDefinitionLoader::class, $asset_definition_loader->loader);
    }
    public function test_invalid_scheme()
    {
        $asset_definition_oader = new AssetDefinitionLoader('<http://www.caiselian.com>');
        $this->assertNull($asset_definition_loader->load_definition());
    }

 
}
