<?php
namespace youkchan\OpenassetsPHP\Tests\Protocol;
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Protocol\OaTransactionOutput;
use youkchan\OpenassetsPHP\Protocol\OutputType;
use youkchan\OpenassetsPHP\Openassets;
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
            );

        }
        else if ($this->coin_name == "monacointestnet") {
            $params = array(
                  "rpc_user" => "mona",
                  "rpc_password" => "mona",
            );
        }

        $this->openassets = new Openassets($params); 

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

}
