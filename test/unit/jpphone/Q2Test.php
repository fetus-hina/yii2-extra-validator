<?php
namespace jp3cki\yii2\validators\test\jpphone;

use Yii;
use jp3cki\yii2\validators\JpPhoneNumberValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class Q2Test extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    public function testQ2()
    {
        $o = new Target();
        $o->types = Target::FLAG_DIAL_Q2;
        $o->hyphen = null;
        $this->assertTrue($o->validate('0990504123'));
        $this->assertTrue($o->validate('0990-504-123'));
        $this->assertTrue($o->validate('0990-50-4123'));
        $this->assertFalse($o->validate('0990-5-04123'));
        $this->assertFalse($o->validate('0990-5041-23'));

        $o->hyphen = true;
        $this->assertfalse($o->validate('0990504123'));
        $this->assertTrue($o->validate('0990-504-123'));
        $this->assertTrue($o->validate('0990-50-4123'));

        $o->hyphen = false;
        $this->assertTrue($o->validate('0990504123'));
        $this->assertFalse($o->validate('0990-504-123'));
        $this->assertFalse($o->validate('0990-50-4123'));
    }

    // フラグが動作することを確認
    public function testFlag()
    {
        $o = new Target();
        foreach (['0990-504-123'] as $number) {
            $o->types = Target::FLAG_DIAL_Q2;
            $this->assertTrue($o->validate($number));
            $o->types = 0;
            $this->assertFalse($o->validate($number));
        }
    }
}
