<?php
namespace youkchan\OpenassetsPHP;
use GuzzleHttp\Client;

class Provider
{

/*
curl --data-binary '{"jsonrpc":"1.0","id":"jsonrpc","method":"getinfo","params":[]}' -H 'content-type:json;' http://rpc:rpc@localhost:19402
*/
    public function post(){
$url = "http://rpc:rpc@localhost:19402";
$client = new Client();
$res = $client->request('POST', $url, [
    'json' => [
      'method' => 'listunspent',
      'jsonrpc' => '1.0',
      'id' => 'jsonrpc',
    ],
    'headers' => [
      'Content-Type' => 'json',
    ]
]);
$result = json_decode($res->getBody());
var_dump($result->result);
echo "test";
    }
}
