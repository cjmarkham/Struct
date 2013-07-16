<?php
/**
 * Created by JetBrains PhpStorm.
 * User: andrew
 * Date: 14/07/2013
 * Time: 00:31
 * To change this template use File | Settings | File Templates.
 */

namespace WillisHQ\Test;

use WillisHQ\StructException;

include('TestStruct.php');

class StructTest extends \PHPUnit_Framework_TestCase
{
    protected $struct;

    public function setUp()
    {

        $this->struct = new TestStruct();
    }

    public function testSetOnConstruct()
    {
        $struct = new TestStruct(array(
            'username' => 'Andrew',
            'email'    => 'andrew@willisilliw.com',
            'id'       => 1,
            'key'      => '%$£$%AQWSDERF'
        ));

        $this->assertEquals('Andrew', $struct->username);
        $this->assertEquals('andrew@willisilliw.com', $struct->email);
        $this->assertEquals('1', $struct->id);
        $this->assertEquals('%$£$%AQWSDERF', $struct->key);

        unset($struct);
        $invalid = false;
        try {
            $struct = new TestStruct(array(
                'username' => 'Andrew',
                'email'    => 'andrew@willisilliw.com',
                'id'       => 1,
                'key'      => '%$£$%AQWSDERF',
                'invalid'  => 'value'
            ));
        } catch (StructException $e) {
            $invalid = true;
        }

        $this->assertTrue($invalid);
    }

    public function testSetOninvoke()
    {
        $struct = clone $this->struct;
        $struct(array(
                'username' => 'Andrew',
                'email'    => 'andrew@willisilliw.com',
                'id'       => 1,
                'key'      => '%$£$%AQWSDERF'
            ));

        $this->assertEquals('Andrew', $struct->username);
        $this->assertEquals('andrew@willisilliw.com', $struct->email);
        $this->assertEquals('1', $struct->id);
        $this->assertEquals('%$£$%AQWSDERF', $struct->key);

        $invalid = false;
        try {
            $struct = clone $this->struct;
            $struct(array(
                    'username' => 'Andrew',
                    'email'    => 'andrew@willisilliw.com',
                    'id'       => 1,
                    'key'      => '%$£$%AQWSDERF',
                    'invalid'  => 'value'
                ));
        } catch (StructException $e) {
            $invalid = true;
        }

        $this->assertTrue($invalid);
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
        $this->assertNull($this->struct->username); // __unset should set value to null
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

    public function testValidatorFilterFail()
    {
        try {
            $this->struct->email = "notanemailaddress";
            $invalid = false;
        } catch (\WillisHQ\StructException $e) {
            $invalid = true;
        }
        $this->struct->email = "andrew@willisilliw.com";
        $this->assertTrue($invalid);
    }

    public function testValueAsArray()
    {
        $this->struct->username = "Andrew";
        $this->struct->email = "andrew@willisilliw.com";
        $this->struct->id = 1;
        $this->struct->key = '%$£$%AQWSDERF';

        $this->assertEquals($this->struct->id, $this->struct['id']);
        $this->assertEquals($this->struct->username, $this->struct['username']);
        $this->assertEquals($this->struct->email, $this->struct['email']);
        $this->assertEquals($this->struct->key, $this->struct['key']);
    }
}