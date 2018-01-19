<?php
namespace youkchan\OpenassetsPHP;
use GuzzleHttp\Client;
use Exception;

class Provider
{

    private $network;
    
    public function __construct($network) {
        $this->network = $network;
    }

    public function list_unspent($addresses = []) {
        $params = array(
            $this->network->get_min_confirmation(),
            $this->network->get_max_confirmation(),
            $addresses,
        );
        $result = array();
        try {
            $result = self::request("listunspent", $params);
        } catch (Exception $e) {
            //No Execution
        }
        return $result;

    }

    public function sign_transaction($hashed_transaction) {
        $params = array(
            $hashed_transaction,
        );
        $result = array();
        try {
            $result = self::request("signrawtransaction", $params);
        } catch (Exception $e) {
            //No Execution
        }
        return $result;

    }

    public function send_transaction($signed_transaction) {
        $params = array(
            $signed_transaction,
        );
        $result = array();
        try {
            $result = self::request("sendrawtransaction", $params);
        } catch (Exception $e) {
            //No Execution
        }
        return $result;

    }
    public function estimate_smartfee($confirmation) {
        $params = array(
            $confirmation,
        );       
        $result = array();
        try {
            $result = self::request("estimatesmartfee", $params);

            if (!is_object($result) || !property_exists($result, 'feerate')) {
                throw new Exception("invalid response");
            }
            $result = $result->feerate;
        } catch (Exception $e) {
            //No Execution
        }
        return $result;

    }

    public function get_transaction($transaction_hash, $verbose = 0){
        $params = array(
            $transaction_hash,
            $verbose,
        );
        $result = array();
        try {
            $result = self::request("getrawtransaction", $params);
        } catch (Exception $e) {
            //No Execution
        }
        return $result;
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
            throw new Exception($e->getMessage());
        } 
        $result = json_decode($response->getBody());
        return $result->result;
   
    }
}
