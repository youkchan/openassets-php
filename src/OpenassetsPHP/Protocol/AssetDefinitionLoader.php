<?php
namespace youkchan\OpenassetsPHP\Protocol;
use youkchan\OpenassetsPHP\Protocol\HttpAssetDefinitionLoader;

class AssetDefinitionLoader
{
    public $loader;
    public function __construct($metadata)
    {
        if (!filter_var($metadata, FILTER_VALIDATE_URL) === false) {
            $this->loader = new HttpAssetDefinitionLoader($metadata);
        }
    }
    public function load_definition()
    {
        if (!$this->loader) {
            return null;
        }
        return $this->loader->load();
    }
}
