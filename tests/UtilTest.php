<?php
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Util;
use youkchan\OpenassetsPHP\Openassets;

class UtilTest extends TestCase
{

    public function setUp(){
        $this->openassets = new Openassets(); 
    }
 
    public function test_convert_oa_address_to_address(){
        $address = 'b6NdFLNHmvbMafTrdRKuoKDnTnVuesayKj6';
        $result = Util::convert_oa_address_to_address($address);
        $this->assertSame($result,'MCfN6CUST7TtoDhGNhocfMstStjUr8SFNT');
    }

    public function test_validate_addresses() {
        //$address_list = ['MCfN6CUST7TtoDhGNhocfMstStjUr8SFNT'];
        $address_list = ['mzi2Dbx1Q9gdFHhJga2rEhyMaUT5QuMrk3'];
        //$address_list = ['3EktnHQD7RiAE6uzMj2ZifT9YgRrkSgzQX'];
        try {
            Util::validate_addresses($address_list , $this->openassets->get_network());
        } catch(Exception $e) {
            $this->fail($e->getMessage());
        }
        $this->assertTrue(true);

    }

}
