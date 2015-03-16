<?php
namespace jp3cki\yii2\validators\test\jpphone;

use Yii;
use jp3cki\yii2\validators\JpPhoneNumberValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class NavDialTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    public function testNavDial()
    {
        $o = new Target();
        $o->types = Target::FLAG_NAV_DIAL;
        $o->hyphen = null;
        $this->assertTrue($o->validate('0570000123'));
        $this->assertTrue($o->validate('0570-000-123'));
        $this->assertTrue($o->validate('0570-00-0123'));
        $this->assertFalse($o->validate('0570-0001-23'));
        $this->assertFalse($o->validate('0120-000123'));

        $o->hyphen = true;
        $this->assertfalse($o->validate('0570000123'));
        $this->assertTrue($o->validate('0570-000-123'));
        $this->assertTrue($o->validate('0570-00-0123'));

        $o->hyphen = false;
        $this->assertTrue($o->validate('0570000123'));
        $this->assertFalse($o->validate('0570-000-123'));
        $this->assertFalse($o->validate('0570-00-0123'));
    }

    // フラグが動作することを確認
    public function testFlag()
    {
        $o = new Target();
        foreach (['0570-000-123'] as $number) {
            $o->types = Target::FLAG_NAV_DIAL;
            $this->assertTrue($o->validate($number));
            $o->types = 0;
            $this->assertFalse($o->validate($number));
        }
    }
}
