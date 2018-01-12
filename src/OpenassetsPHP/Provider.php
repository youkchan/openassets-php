<?php
namespace youkchan\OpenassetsPHP;
use GuzzleHttp\Client;
//use youkchan\OpenassetsPHP\Network;
use Exception;

class Provider
{

/*
curl --data-binary '{"jsonrpc":"1.0","id":"jsonrpc","method":"getinfo","params":[]}' -H 'content-type:json;' http://rpc:rpc@localhost:19402
*/

    private $network;
    
    public function __construct($network) {
        $this->network = $network;
    }

    public function list_unspent($addresses = []) {
        $params = array(
            $this->network->get_min_confirmation(),
            $this->network->get_max_confirmation(),
        );
        //$result = self::request("listunspent", json_encode($params));
        $result = self::request("listunspent", $params);
        //return $result;

    }

    protected function request($command, $params) {
        $url = $this->network->get_server_url();
        $timeout = $this->network->get_timeout();
        $client = new Client();
        try {
            $response = $client->request('POST', $url, [
                          'json' => [
                            'method' => $command,
                            'jsonrpc' => '1.0',
                            'id' => 'jsonrpc',
                            'params' => $params,
                          ],
                          'timeout' => $timeout,
                          'headers' => [
                            'Content-Type' => 'json',
                          ]
                      ]);
        }catch (Exception $e) {
        } 
//$result = json_decode($result->getBody());
//var_dump($result);
   
    }
/*
    protected function post() {
        $url = $this->network->get_server_url();
//$url = "http://rpc:rpc@localhost:19402";
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
    }*/
}
