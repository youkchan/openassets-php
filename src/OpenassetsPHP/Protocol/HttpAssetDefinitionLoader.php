<?php
namespace youkchan\OpenassetsPHP\Protocol;
use youkchan\OpenassetsPHP\Protocol\HttpAssetDefinitionLoader;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\TransferException;

class HttpAssetDefinitionLoader
{

    protected $url;
    public function __construct($url)
    {
        $this->url = $url;
    }
    public function load()
    {
        $client = new Client();
        try {
            $response = $client->request('GET', $this->url);
            if ($response->getStatusCode() != 200) {
                return null;
            }
            $body = $response->getBody();
            $definition = AssetDefinition::parse_json($body);
            $definition->asset_definition_url = $this->url;
        } catch (TransferException $e) {
            return null;
        }
        return $definition;
    }
}
