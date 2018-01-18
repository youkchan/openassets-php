<?php
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Provider;
use youkchan\OpenassetsPHP\Network;
use youkchan\OpenassetsPHP\Openassets;

use BitWasp\Buffertools\Buffer;
use BitWasp\Bitcoin\Transaction\TransactionFactory;
class ProviderTest extends TestCase
{

    public function setUp(){
        $this->network = new Network(); 
        $this->provider = new Provider($this->network);
    }

    public function test_list_unspent(){
        $address = ['mzi2Dbx1Q9gdFHhJga2rEhyMaUT5QuMrk3'];
        $results_comp = array (
            array (
                "txid" => "2cc47ae42e08bd43383697dfc6afe1d8e779aac3a4bf76af85acc71d5a2a9d67",
                "vout" => 1,
                "address" => "mzi2Dbx1Q9gdFHhJga2rEhyMaUT5QuMrk3",
                "account" => "",
                "scriptPubKey" => "76a914d2823d8dfec224caceb546aa2c1f43999ccb578688ac",
                "amount" => 0.39000000,
                "confirmations" => 805,
                "spendable" => true,
                "solvable" => true,
            ),
            array (
                "txid" => "18a9aadd4c6d2c8eb05eaec8c71ec09b3dd202bd863017227ad6a1a2d2bc41b5",
                "vout" => 1,
                "address" => "mzi2Dbx1Q9gdFHhJga2rEhyMaUT5QuMrk3",
                "account" => "",
                "scriptPubKey" => "76a914d2823d8dfec224caceb546aa2c1f43999ccb578688ac",
                "amount" => 212.94000000,
                "confirmations" => 805,
                "spendable" => true,
                "solvable" => true,

            ),
        );
        //$results = $this->provider->list_unspent($address, $this->network);
       /* 
        $this->assertSame($results_comp[0]["txid"],$results[0]->txid);
        $this->assertSame($results_comp[0]["vout"],$results[0]->vout);
        $this->assertSame($results_comp[0]["address"],$results[0]->address);
        $this->assertSame($results_comp[0]["account"],$results[0]->account);
        $this->assertSame($results_comp[0]["scriptPubKey"],$results[0]->scriptPubKey);
        $this->assertSame($results_comp[0]["amount"],$results[0]->amount);
        $this->assertGreaterThan($results_comp[0]["confirmations"],$results[0]->confirmations);
        $this->assertSame($results_comp[0]["spendable"],$results[0]->spendable);
        $this->assertSame($results_comp[0]["solvable"],$results[0]->solvable);

        $this->assertSame($results_comp[1]["txid"],$results[1]->txid);
        $this->assertSame($results_comp[1]["vout"],$results[1]->vout);
        $this->assertSame($results_comp[1]["address"],$results[1]->address);
        $this->assertSame($results_comp[1]["account"],$results[1]->account);
        $this->assertSame($results_comp[1]["scriptPubKey"],$results[1]->scriptPubKey);
        $this->assertSame($results_comp[1]["amount"],$results[1]->amount);
        $this->assertGreaterThan($results_comp[1]["confirmations"],$results[1]->confirmations);
        $this->assertSame($results_comp[1]["spendable"],$results[1]->spendable);
        $this->assertSame($results_comp[1]["solvable"],$results[1]->solvable);
*/
    }

    public function test_estimate_smartfee() {
        $result = $this->provider->estimate_smartfee(1);
        var_dump($result);
    }

    public function test_get_transaction(){
    
        $transaction_hash = "2cc47ae42e08bd43383697dfc6afe1d8e779aac3a4bf76af85acc71d5a2a9d67";
        $verbose_1 = 1;
        $verbose_0 = 0;

        $result_comp = "0100000001041beb42511fa1505b5d840c8cd2804978863704e9a86451cb9d5d93465ce579000000006b483045022100ac5c7ff62561bfce0e53d967b6394a24d192d02c7044a21aac65c8cc1c9527ed02203edb1136b362ce6467d961c5436baea54d95d5ad02bcaf809f3b5826fdc92206012103a31a542ddf0e9b829d47b8a9d19d1346430416eab2fbdc55b5074b777c335a74ffffffff030000000000000000346a325468616e6b20796f752e2048656c6c6f2066726f6d20544f524946554b552e204d6f6e61636f696e20697320477265617421c0175302000000001976a914d2823d8dfec224caceb546aa2c1f43999ccb578688aca053b127010000001976a914b184dcb0b6b4936a96e84f567b87ecfcfa97a9e488ac00000000";
        //$result = $this->provider->get_transaction($transaction_hash, $verbose_0);
        //$this->assertSame($result_comp, $result);
        
        //$results = $this->provider->get_transaction($transaction_hash, $verbose_1);
        //$this->assertSame($result_comp, $results->hex);

        //$result = $this->provider->get_transaction("94dbae46ad86b14387b54499e05ef716af468f2833f8fc608c13813decdd78ba", $verbose_0);
//var_dump(pack('H*', $result));
        //$this->assertTrue(empty($result));
        //$result = $this->provider->get_transaction("18a9aadd4c6d2c8eb05eaec8c71ec09b3dd202bd863017227ad6a1a2d2bc41b5", $verbose_0);
//var_dump($result);
//var_dump(TransactionFactory::build());
//$hex = "0100000001871b65791bc40686fbafe60507444cd2c778a8e8eea4cde8f11ac18cef02498a000000006a473044022009e7de84d42a7d9d2bfd37a93f33b6552fe9526c1fcfb246b18e8e5e62a4f08302200f16099be24eb0c9164a3cb24acb6cbf3a3398aab4e7931f90f4dd58414dee1d012102332c0d558d38e2c874077834ddbbcff9621eadb365397b237ea64eb6e31e0211ffffffff0358020000000000001976a914e7d9217ed5a17650403bb8f8d59ef442198ad69a88ac00000000000000001f6a1d4f41010001641668747470733a2f2f686f6765686f67652e636f2e6a70581bf505000000001976a914e7d9217ed5a17650403bb8f8d59ef442198ad69a88ac00000000";
//var_dump(TransactionFactory::fromHex($hex));
    }
}
