<?php
namespace youkchan\OpenassetsPHP\Tests\Protocol;
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Openassets;
use youkchan\OpenassetsPHP\Protocol\MarkerOutput;
require_once "../Bootstrap.php";

class MarkerOutputTest extends TestCase
{

    public function setUp(){
        $this->coin_name = get_run_coin_name();
        $params = array();
        if ($this->coin_name == "litecointestnet") {
            $params = array(
                    "network" =>"litecoinTestnet", 
                    "cache" =>false,
            );

        }
        else if ($this->coin_name == "monacointestnet") {
            $params = array(
                  "rpc_user" => "mona",
                  "rpc_password" => "mona",
            );
        }

        $this->openassets = new Openassets($params); 
        ini_set('xdebug.var_display_max_children', -1);
        ini_set('xdebug.var_display_max_data', -1);
        ini_set('xdebug.var_display_max_depth', -1);
        if ($this->coin_name == "litecointestnet") {
        }
        else if ($this->coin_name == "monacointestnet") {
            $this->asset_quantities = [100];
            $this->metadata = "u=http://160.16.208.215/token_service_test/api/v1/asset/TEST2";
            $this->marker_output = new MarkerOutput($this->asset_quantities, $this->metadata);
        }
        else {
            $this->fail("node not run.");
        }
    }

    public function test_serialize_payload(){
        if ($this->coin_name == "litecointestnet") {
        } else if ($this->coin_name == "monacointestnet") {
            $payload = $this->marker_output->serialize_payload();
            $this->assertEquals($payload, "4f41010001643d753d687474703a2f2f3136302e31362e3230382e3231352f746f6b656e5f736572766963655f746573742f6170692f76312f61737365742f5445535432");
        }
        else {
            $this->fail("node not run.");
        }

    }
    public function test_deserialize_payload(){
        if ($this->coin_name == "litecointestnet") {
        } else if ($this->coin_name == "monacointestnet") {
            $marker_output = MarkerOutput::deserialize_payload("4f41010001643d753d687474703a2f2f3136302e31362e3230382e3231352f746f6b656e5f736572766963655f746573742f6170692f76312f61737365742f5445535432");
            $this->assertEquals($marker_output, $this->marker_output);
        }
        else {
            $this->fail("node not run.");
        }


    }

}
