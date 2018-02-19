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
        }
        else {
            $this->fail("node not run.");
        }

    }

    public function test_initialize() {
        if ($this->coin_name == "litecointestnet") {
            $decode_transaction = $this->openassets->load_cached_transaction("64c25a7f4c17aac23d032bebd8903ff06d0c8ce2087a350102c774336d8e82be");
            $transaction = TransactionFactory::fromHex($decode_transaction);
        $colored_outputs = $this->openassets->get_color_outputs_from_tx($transaction);
//var_dump($transaction->getOutput(1));
//var_dump($transaction);
//var_dump($colored_outputs);
            $output = new OaTransactionOutput(600 , $transaction->getOutput(1)->getScript(), "oTrSyX7oZKSournnQ46gkM2c3TXMqyYK3Q" , 20 ,OutputType::TRANSFER, "u=http://160.16.208.215/token_service_test/api/v1/asset/TEST2", $this->openassets->get_network());
var_dump($output);

        }
        else if ($this->coin_name == "monacointestnet") {
        }
        else {
            $this->fail("node not run.");
        }
    }

}
