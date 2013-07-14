<?php
/**
 * Created by JetBrains PhpStorm.
 * User: andrew
 * Date: 14/07/2013
 * Time: 00:31
 * To change this template use File | Settings | File Templates.
 */

namespace WillisHQ\Test;

include('TestStruct.php');

class StructTest extends \PHPUnit_Framework_TestCase
{
    protected $struct;

    public function setUp()
    {

        $this->struct = new TestStruct();
    }

    public function testPropertyAssignment()
    {
        $this->struct->username = "Andrew";
        $this->struct->email = "andrew@willisilliw.com";
        $this->struct->id = 1;
        $this->struct->key = '%$£$%AQWSDERF';

        $this->assertEquals("Andrew", $this->struct->username);
        $this->assertEquals("andrew@willisilliw.com", $this->struct->email);
        $this->assertEquals(1, $this->struct->id);
        $this->assertEquals('%$£$%AQWSDERF', $this->struct->key);
    }

    public function testPropertyUnset()
    {
        $this->struct->username = "Andrew";
        $this->assertEquals("Andrew", $this->struct->username);
        unset($this->struct->username);
        $this->assertFalse(isset($this->struct->username));
    }

    public function testInvalidPropertyAssignment()
    {
        try {
            $this->struct->invalid = 'value';
            $invalid = false;
        } catch (\WillisHQ\StructException $e) {
            $invalid = true;
        }

        $this->assertTrue($invalid);
    }

    public function testFilterFail()
    {
        try {
            $this->struct->id = 'Hello';
            $invalid = false;
        } catch (\WillisHQ\StructException $e) {
            $invalid = true;
        }
        $this->struct->id = 1;
        $this->assertTrue($invalid);
    }

    public function testValueAsArray()
    {
        $this->assertEquals($this->struct->id, $this->struct['id']);
        $this->assertEquals($this->struct->username, $this->struct['username']);
        $this->assertEquals($this->struct->email, $this->struct['email']);
        $this->assertEquals($this->struct->key, $this->struct['key']);
    }
}