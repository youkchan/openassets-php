<?php
namespace youkchan\OpenassetsPHP\Tests\Protocol;
use PHPUnit\Framework\TestCase;
use youkchan\OpenassetsPHP\Protocol\OutputType;

class OutputTypeTest extends TestCase
{

    public function test_is_label(){
        $this->assertTrue(OutputType::isLabel(0));
        $this->assertTrue(OutputType::isLabel(1));
        $this->assertTrue(OutputType::isLabel(2));
        $this->assertTrue(OutputType::isLabel(3));
        $this->assertTrue(!OutputType::isLabel(4));
    }


    public function test_output_type_label(){
        $this->assertEquals("uncolored", OutputType::output_type_label(0));
        $this->assertEquals("marker", OutputType::output_type_label(1));
        $this->assertEquals("issuance", OutputType::output_type_label(2));
        $this->assertEquals("transfer", OutputType::output_type_label(3));
        $this->assertEquals("uncolored", OutputType::output_type_label(4));
    }
}
