<?php
namespace youkchan\OpenassetsPHP;
use GuzzleHttp\Client;

class Provider
{

/*
curl --data-binary '{"jsonrpc":"1.0","id":"jsonrpc","method":"getinfo","params":[]}' -H 'content-type:json;' http://rpc:rpc@localhost:19402
*/


    public function request($comand, $params) {
    
    }

    public function post() {
$url = "http://rpc:rpc@localhost:19402";
$client = new Client();
$res = $client->request('POST', $url, [
    'json' => [
      'method' => 'listunspent',
      'jsonrpc' => '1.0',
      'id' => 'jsonrpc',
    ],
    'timeout' => 60,
    'headers' => [
      'Content-Type' => 'json',
    ]
]);
$result = json_decode($res->getBody());
var_dump($result->result);
echo "test";
    }
}
