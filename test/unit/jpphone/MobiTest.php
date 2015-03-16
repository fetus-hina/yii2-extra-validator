<?php
namespace jp3cki\yii2\validators\test\jpphone;

use Yii;
use jp3cki\yii2\validators\JpPhoneNumberValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class MobiTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    public function test090()
    {
        $o = new Target();
        $o->types = Target::FLAG_MOBILE;
        $o->hyphen = null;
        $this->assertTrue($o->validate('09010091234'));
        $this->assertTrue($o->validate('090-1009-1234'));
        $this->assertFalse($o->validate('090-10091234'));
        $this->assertFalse($o->validate('090-100-91234'));

        $o->hyphen = true;
        $this->assertfalse($o->validate('09010091234'));
        $this->assertTrue($o->validate('090-1009-1234'));

        $o->hyphen = false;
        $this->assertTrue($o->validate('09010091234'));
        $this->assertFalse($o->validate('090-1009-1234'));
    }

    public function test080()
    {
        $o = new Target();
        $o->types = Target::FLAG_MOBILE;
        $o->hyphen = null;
        $this->assertTrue($o->validate('08010091234'));
        $this->assertTrue($o->validate('080-1009-1234'));
        $this->assertFalse($o->validate('080-10091234'));
        $this->assertFalse($o->validate('080-100-91234'));

        $o->hyphen = true;
        $this->assertfalse($o->validate('08010091234'));
        $this->assertTrue($o->validate('080-1009-1234'));

        $o->hyphen = false;
        $this->assertTrue($o->validate('08010091234'));
        $this->assertFalse($o->validate('080-1009-1234'));

        // 080 は 0800 と紛らわしい
        $o->hyphen = false;
        $this->assertFalse($o->validate('08009876543'));
    }

    public function test070()
    {
        $o = new Target();
        $o->types = Target::FLAG_MOBILE;
        $o->hyphen = null;
        $this->assertTrue($o->validate('07050191234'));
        $this->assertTrue($o->validate('070-5019-1234'));
        $this->assertFalse($o->validate('070-50191234'));
        $this->assertFalse($o->validate('070-100-91234'));

        $o->hyphen = true;
        $this->assertfalse($o->validate('07050191234'));
        $this->assertTrue($o->validate('070-5019-1234'));

        $o->hyphen = false;
        $this->assertTrue($o->validate('07050191234'));
        $this->assertFalse($o->validate('070-5019-1234'));
    }

    // 携帯電話フラグが動作することを確認
    public function testFlag()
    {
        $o = new Target();
        foreach (['09010091234', '08010091234', '07050191234'] as $number) {
            $o->types = Target::FLAG_MOBILE;
            $this->assertTrue($o->validate($number));
            $o->types = 0;
            $this->assertFalse($o->validate($number));
        }
    }
}
