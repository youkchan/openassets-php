<?php
namespace youkchan\OpenassetsPHP\Tests\Protocol;
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Protocol\OaTransactionOutput;
use youkchan\OpenassetsPHP\Protocol\OutputType;
use youkchan\OpenassetsPHP\Openassets;
use BitWasp\Bitcoin\Transaction\TransactionFactory;
use BitWasp\Bitcoin\Script\Script;
use BitWasp\Buffertools\Buffer;
require_once "../Bootstrap.php";

class OaTransactionOutputTest extends TestCase
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
            $decode_transaction = $this->openassets->load_cached_transaction("64c25a7f4c17aac23d032bebd8903ff06d0c8ce2087a350102c774336d8e82be");
            $transaction = TransactionFactory::fromHex($decode_transaction);
            $colored_outputs = $this->openassets->get_color_outputs_from_tx($transaction);
//var_dump($transaction->getOutput(1));
//var_dump($transaction);
//var_dump($colored_outputs);
            $this->output = new OaTransactionOutput(600 , $transaction->getOutput(1)->getScript(), "oTrSyX7oZKSournnQ46gkM2c3TXMqyYK3Q" , 20 ,OutputType::TRANSFER, "u=http://160.16.208.215/token_service_test/api/v1/asset/TEST2", $this->openassets->get_network());
//var_dump($output);

        }
        else if ($this->coin_name == "monacointestnet") {
            $decode_transaction = $this->openassets->load_cached_transaction("80216fe600fd4cdd833e1ad747c6fa9fa490d7a30e60e31c97b8656d23db0665");
            $transaction = TransactionFactory::fromHex($decode_transaction);
            $colored_outputs = $this->openassets->get_color_outputs_from_tx($transaction);
            $this->value = 600;
            $this->script = $transaction->getOutput(1)->getScript();
            $this->asset_id = "oTrSyX7oZKSournnQ46gkM2c3TXMqyYK3Q";
            $this->asset_quantity = 100;
            $this->output_type = OutputType::TRANSFER;
            $this->metadata = "u=http://160.16.208.215/token_service_test/api/v1/asset/TEST2";
            $this->output = new OaTransactionOutput($this->value , $this->script, $this->asset_id, $this->asset_quantity, $this->output_type, $this->metadata, $this->openassets->get_network());
        }
        else {
            $this->fail("node not run.");
        }
    }

    public function test_get_value() {
        $this->assertEquals($this->output->get_value(), $this->value);
    }

    public function test_get_script() {
        $this->assertEquals($this->output->get_script(), $this->script);
    }

    public function test_get_asset_id() {
        $this->assertEquals($this->output->get_asset_id(), $this->asset_id);
    }

    public function test_get_asset_quantity() {
        $this->assertEquals($this->output->get_asset_quantity(), $this->asset_quantity);
    }

    public function test_get_metadata() {
        $this->assertEquals($this->output->get_metadata(), $this->metadata);
    }

    public function test_get_metadata_url() {
        if ($this->coin_name == "litecointestnet") {
        } else if ($this->coin_name == "monacointestnet") {
            $this->assertEquals($this->output->get_metadata_url(), "http://160.16.208.215/token_service_test/api/v1/asset/TEST2");
        }
        else {
            $this->fail("node not run.");
        }
    }

    public function test_get_load_asset_definition_url() {
        if ($this->coin_name == "litecointestnet") {
        } else if ($this->coin_name == "monacointestnet") {
            $this->assertEquals($this->output->get_load_asset_definition_url(), "http://160.16.208.215/token_service_test/api/v1/asset/TEST2");
        }
        else {
            $this->fail("node not run.");
        }
    }

    public function test_load_asset_definition(){
        $result = $this->output->load_asset_definition("http://160.16.208.215/token_service_test/api/v1/asset/TEST2");
        $this->assertEquals($result->asset_ids[0], "oTrSyX7oZKSournnQ46gkM2c3TXMqyYK3Q");
        $this->assertEquals($result->asset_definition_url, "http://160.16.208.215/token_service_test/api/v1/asset/TEST2");
        $this->assertEquals($result->name_short, "TEST2");
        $this->assertEquals($result->name, "Fullname");
        $this->assertEquals($result->image_url, "http://prueba-semilla.org:88/image/neko1.jpg");
        $this->assertEquals($result->version, "1.0.0");
    }

    public function test_get_asset_amount() {
    }

    public function test_get_divisibility() {
    }

    public function test_get_proof_of_authenticity() {
    }

    public function test_get_address() {
        if ($this->coin_name == "litecointestnet") {
        } else if ($this->coin_name == "monacointestnet") {
            $this->assertEquals($this->output->get_address(), "mz4Eictz7aZqQLURarNWqA1ureefRgQ4xR");
        }
        else {
            $this->fail("node not run.");
        }
    }

    public function test_get_oa_address() {
        if ($this->coin_name == "litecointestnet") {
        } else if ($this->coin_name == "monacointestnet") {
            $this->assertEquals($this->output->get_oa_address(), "bXA27xniKb4TXGadndUUhV1vVCFpqPLQHZN");
        }
        else {
            $this->fail("node not run.");
        }
    }

    public function test_valid_asset_definition() {
        if ($this->coin_name == "litecointestnet") {
        } else if ($this->coin_name == "monacointestnet") {
            $this->assertTrue($this->output->valid_asset_definition());
            $output = $this->output;
            $output->asset_id = "oWDNLde2LweGTgsVgtx6XcNNDZvm8kWnj1";
            $this->assertFalse($output->valid_asset_definition());
            $output->asset_definition = null;
            $this->assertFalse($output->valid_asset_definition());
            $output->asset_id = null;
            $this->assertFalse($output->valid_asset_definition());
        }
        else {
            $this->fail("node not run.");
        }
    }



    public function test_to_hash(){
        if ($this->coin_name == "litecointestnet") {
            $buffer = Buffer::hex("76a9140e52fd303cd6d1434bd5cdbbc95dda5a05d2d3c988ac");
            $script = new Script($buffer);
            $output = new OaTransactionOutput(57400, $script, "oHhZWY665rNoSuqJ5pEMLSqzf3R1QPYLyp" , 20 ,OutputType::ISSUANCE, "u=http://test.co.jp", $this->openassets->get_network());
            $result = $output->to_hash();
            $this->assertEquals($result["address"] , "mgphAvBFVKcbCA28aYjaPTtSCAQfuBhqYM");
            $this->assertEquals($result["oa_address"] , "bWrnaR5zaxoWH4QBVdAqm3Ko1XmaqycNi8h");
            $this->assertEquals($result["script"] , "76a9140e52fd303cd6d1434bd5cdbbc95dda5a05d2d3c988ac");
            $this->assertEquals($result["script_type"] , "pubkeyhash");
            $this->assertEquals($result["amount"] , 0.000574);
            $this->assertEquals($result["asset_id"] , "oHhZWY665rNoSuqJ5pEMLSqzf3R1QPYLyp");
            $this->assertEquals($result["asset_quantity"] , 20);
            $this->assertEquals($result["asset_amount"] , 20);
            $this->assertEquals($result["asset_definition_url"] , "The asset definition is invalid. http://test.co.jp");
            $this->assertEquals($result["proof_of_authenticity"] , false);
            $this->assertEquals($result["output_type"] , "issuance");

        }
        else if ($this->coin_name == "monacointestnet") {
            $result = $this->output->to_hash();
            $this->assertEquals($result["address"] , "mz4Eictz7aZqQLURarNWqA1ureefRgQ4xR");
            $this->assertEquals($result["oa_address"] , "bXA27xniKb4TXGadndUUhV1vVCFpqPLQHZN");
            $this->assertEquals($result["script"] , "76a914cb5cde5d340d498c3be4533891173ec052878ab788ac");
            $this->assertEquals($result["script_type"] , "pubkeyhash");
            $this->assertEquals($result["amount"] , 0.00000600);
            $this->assertEquals($result["asset_id"] , "oTrSyX7oZKSournnQ46gkM2c3TXMqyYK3Q");
            $this->assertEquals($result["asset_quantity"] , 100);
            $this->assertEquals($result["asset_amount"] , 100);
            $this->assertEquals($result["asset_definition_url"] , "http://160.16.208.215/token_service_test/api/v1/asset/TEST2");
            $this->assertEquals($result["proof_of_authenticity"] , false);
            $this->assertEquals($result["output_type"] , "transfer");

        }
        else {
            $this->fail("node not run.");
        }

    }


}
