<?php
namespace jp3cki\yii2\validators\test\jpphone;

use Yii;
use jp3cki\yii2\validators\JpPhoneNumberValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class FreeDialTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    public function testFreeDial()
    {
        $o = new Target();
        $o->types = Target::FLAG_FREE_DIAL;
        $o->hyphen = null;
        $this->assertTrue($o->validate('0120123456'));
        $this->assertTrue($o->validate('0120-123-456'));
        $this->assertTrue($o->validate('0120-12-3456'));
        $this->assertFalse($o->validate('0120-1-23456'));
        $this->assertFalse($o->validate('0120-123456'));

        $o->hyphen = true;
        $this->assertfalse($o->validate('0120123456'));
        $this->assertTrue($o->validate('0120-123-456'));
        $this->assertTrue($o->validate('0120-12-3456'));

        $o->hyphen = false;
        $this->assertTrue($o->validate('0120123456'));
        $this->assertFalse($o->validate('0120-123-456'));
        $this->assertFalse($o->validate('0120-12-3456'));
    }

    public function testFreeAccess()
    {
        $o = new Target();
        $o->types = Target::FLAG_FREE_ACCESS;
        $o->hyphen = null;
        $this->assertTrue($o->validate('08009876543'));
        $this->assertTrue($o->validate('0800-987-6543'));
        $this->assertFalse($o->validate('0800-9876543'));

        $o->hyphen = true;
        $this->assertfalse($o->validate('08009876543'));
        $this->assertTrue($o->validate('0800-987-6543'));

        $o->hyphen = false;
        $this->assertTrue($o->validate('08009876543'));
        $this->assertFalse($o->validate('0800-987-6543'));
    }

    // フラグが動作することを確認
    public function testFlag()
    {
        $o = new Target();
        foreach (['0120-123-456', '0800-987-6543'] as $number) {
            $o->types = Target::FLAG_FREE_DIAL | Target::FLAG_FREE_ACCESS;
            $this->assertTrue($o->validate($number));
            $o->types = 0;
            $this->assertFalse($o->validate($number));
        }
    }
}
