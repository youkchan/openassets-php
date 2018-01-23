<?php
namespace youkchan\OpenassetsPHP\Tests\Transaction;
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Network;
use youkchan\OpenassetsPHP\Transaction\TransactionBuilder;
use BitWasp\Bitcoin\Base58;
use BitWasp\Bitcoin\Address\PayToPubKeyHashAddress;
use BitWasp\Bitcoin\Transaction\TransactionOutput;
use BitWasp\Bitcoin\Script\ScriptFactory;
use BitWasp\Bitcoin\Script\Opcodes;
require_once "Bootstrap.php";


class TransactionBuilderTest extends TestCase
{
    private $transaction_builder;
    private $coin_name;

    public function setUp(){
        $params = array(
         //   "network" =>"monacoinTestnet", 
        );
        $this->network = new Network(); 
        $this->transaction_builder = new TransactionBuilder(600, 10000, $this->network); 
        $this->coin_name = get_run_coin_name();
    }
 

    public function test_create_colored_output(){
        $address = "mo47Pa9osADDGyyu9LZMGrRGTDQgnS2jUN";
        $data = Base58::decodeCheck($address);
        $address_object = new PayToPubKeyHashAddress($data->slice(1)); //Addressクラス、単なるバッファー
        //var_dump($address_object->getHash());
        $script_pub_key = ScriptFactory::sequence([Opcodes::OP_DUP, Opcodes::OP_HASH160, $address_object->getHash(), Opcodes::OP_EQUALVERIFY, Opcodes::OP_CHECKSIG]);
        //$comp_result = new TransactionOutput($this->transaction_builder->amount, $address_object->getScriptPubKey());
        $comp_result = new TransactionOutput($this->transaction_builder->amount, $script_pub_key);

        $result = $this->transaction_builder->create_colored_output($address);
        $this->assertEquals($result, $comp_result);
    }

}
