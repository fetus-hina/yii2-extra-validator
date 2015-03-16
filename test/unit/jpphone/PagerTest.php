<?php
namespace jp3cki\yii2\validators\test\jpphone;

use Yii;
use jp3cki\yii2\validators\JpPhoneNumberValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class PagerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    public function test020()
    {
        $o = new Target();
        $o->types = Target::FLAG_PAGER;
        $o->hyphen = null;
        $this->assertTrue($o->validate('02046091234'));
        $this->assertTrue($o->validate('020-4609-1234'));
        $this->assertFalse($o->validate('020-46091234'));

        $o->hyphen = true;
        $this->assertfalse($o->validate('02046091234'));
        $this->assertTrue($o->validate('020-4609-1234'));

        $o->hyphen = false;
        $this->assertTrue($o->validate('02046091234'));
        $this->assertFalse($o->validate('020-4609-1234'));
    }

    // フラグが動作することを確認
    public function testFlag()
    {
        $o = new Target();
        foreach (['020-4609-1234'] as $number) {
            $o->types = Target::FLAG_PAGER;
            $this->assertTrue($o->validate($number));
            $o->types = 0;
            $this->assertFalse($o->validate($number));
        }
    }
}
