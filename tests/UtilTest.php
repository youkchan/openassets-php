<?php
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Util;

class UtilTest extends TestCase
{
    public function test_convert_oa_address_to_address(){
        $address = 'b6NdFLNHmvbMafTrdRKuoKDnTnVuesayKj6';
        $result = Util::convert_oa_address_to_address($address);
        $this->assertSame($result,'MCfN6CUST7TtoDhGNhocfMstStjUr8SFNT');
    }

}
