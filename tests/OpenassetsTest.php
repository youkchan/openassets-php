<?php
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Openassets;

class OpenassetsTest extends TestCase
{

    private $openassets;

    public function setUp(){
        $this->openassets = new Openassets(); 
    }
 
/*    public function testGetApi(){
        $openassets = new Openassets();
        $api = $openassets->getApi();
        $this->assertSame($api->get("network"),'mainnet');
        $this->assertSame($api->get("provider"),'monacoin');
        $this->assertSame($api->get("cache"),'cache.db');
        $this->assertSame($api->get("dust_limit"),600);
        $this->assertSame($api->get("default_fee"),10000);
        $this->assertSame($api->get("min_confirmation"),1);
        $this->assertSame($api->get("max_confirmation"),9999999);
        $this->assertSame($api->get("rpc_host"),'localhost');
        $this->assertSame($api->get("rpc_user"),'');
        $this->assertSame($api->get("rpc_password"),'');
        $this->assertSame($api->get("rpc_wallet"),'');
        $this->assertSame($api->get("rpc_schema"),'https');
        $this->assertSame($api->get("rpc_timeout"),60);
        $this->assertSame($api->get("rpc_open_timeout"),60);
    }
*/
    public function test_list_unspent(){
        //$result = $this->openassets->list_unspent(["bWy1zdjy9Le6u9E9GBxfXviKqnparoNZRWA"]);
    //    $result = $this->openassets->list_unspent(["bXCcjk3wL8GAtkeoxzzcVj2nfSAN6XCtYEK"]);
    }

    public function test_issue_asset() {
        //$result = $this->openassets->issue_asset("bWy1zdjy9Le6u9E9GBxfXviKqnparoNZRWA",100);
        //$result = $this->openassets->issue_asset("bXCcjk3wL8GAtkeoxzzcVj2nfSAN6XCtYEK",100, "https://test.co.jp",null ,50000);
    }

    public function test_get_unspent_outputs(){
        //$address = ['mzi2Dbx1Q9gdFHhJga2rEhyMaUT5QuMrk3'];
        $address = ['mx7DJCfEW3BXyNatnpXio5VLbqjspFgqdd'];
//        $result = $this->openassets->get_unspent_outputs($address);
        //$address_list = ['MCfN6CUST7TtoDhGNhocfMstStjUr8SFNT'];
    //    $address_list = ['mzi2Dbx1Q9gdFHhJga2rEhyMaUT5QuMrk3'];
        //$address_list = ['3EktnHQD7RiAE6uzMj2ZifT9YgRrkSgzQX'];
    //    $result = $this->openassets->get_unspent_outputs($address_list);
    //    $this->assertSame($result,'OK');
    }

    public function test_load_transaction(){
        $transaction_id = "18a9aadd4c6d2c8eb05eaec8c71ec09b3dd202bd863017227ad6a1a2d2bc41b5";
        
        
    }

    public function test_get_output() {
        //$transaction_id = "da3851496a0cdf5447d53d1a735085532be59b45aadc8961ed464853c283b61c";
        $transaction_id = "54844e349add3a8fe40034072679dcf067f44bcc7571cfc65dc82b031fda6e69"; //issue
        //$transaction_id = "480b6c74a188bfb5c69966f0156c2122ebe134b7aedd650cef50af26a5174746"; //send

        //var_dump($this->openassets->get_output($transaction_id,0));
    }

    /*public function test_parse_issuance_p2sh_pointer() {
        $te = '47304402202254f7da7c3fe2bf2a4dd2c3e255aa3ad61415550f648b564aea335f8fcd3d92022062eab5c01a5e33eb726f976ebd3b35d3991f8a45da56d64e1cd3fd5178f8c9a6012102effb2edfcf826d43027feae226143bdac058ad2e87b7cec26f97af2d357ddefa3217753d68747470733a2f2f676f6f2e676c2f626d564575777576a9148911455a265235b2d356a1324af000d4dae0326288ac';
        return $this->openassets->parse_issuance_p2sh_pointer($te);
        
    }*/

    public function test_create_transaction_builder() {
        $this->openassets->create_transaction_builder();
    }    
}
