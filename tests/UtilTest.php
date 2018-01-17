<?php
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Util;
use youkchan\OpenassetsPHP\Openassets;
use youkchan\OpenassetsPHP\Network;
use BitWasp\Buffertools\Buffer;
use BitWasp\Bitcoin\Script\Script;

class UtilTest extends TestCase
{

    public function setUp(){
        $this->openassets = new Openassets(); 
        $this->network = new Network(); 
    }
 
    public function test_convert_oa_address_to_address(){
        $address = 'b6NdFLNHmvbMafTrdRKuoKDnTnVuesayKj6';
        $result = Util::convert_oa_address_to_address($address);
        $this->assertSame($result,'MCfN6CUST7TtoDhGNhocfMstStjUr8SFNT');
    }

    public function test_convert_address_to_oa_address (){
        $address = 'n2erVt7zenHCtQebxNWK5At66ZBvVXVg3k';
        $address_comp = 'bXCcjk3wL8GAtkeoxzzcVj2nfSAN6XCtYEK';
        $result = Util::convert_address_to_oa_address($address);
        $this->assertSame($result,$address_comp);
    }
    public function test_script_to_asset_id() {
        $script = new Script(Buffer::hex("76a914e7d9217ed5a17650403bb8f8d59ef442198ad69a88ac"));
        $hashed_comp = "odouUF3zqWNyAQdeR4JzJ3sQDSAaqZQZRd";
        $result =  Util::script_to_asset_id($script, $this->network);
        $this->assertEquals($result, $hashed_comp);
    }

    public function test_validate_addresses() {
        //$address_list = ['MCfN6CUST7TtoDhGNhocfMstStjUr8SFNT'];
        $address_list = ['mzi2Dbx1Q9gdFHhJga2rEhyMaUT5QuMrk3'];
        //$address_list = ['3EktnHQD7RiAE6uzMj2ZifT9YgRrkSgzQX'];
        try {
            Util::validate_addresses($address_list , $this->network->get_bclib_network());
        } catch(Exception $e) {
            $this->fail($e->getMessage());
        }
        $this->assertTrue(true);

    }

    public function test_decode_leb128() {
        $result = Util::decode_leb128('e58e26');
        $this->assertEquals(624485, $result[0]);
    }

}
