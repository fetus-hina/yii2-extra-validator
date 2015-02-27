<?php
namespace jp3cki\yii2\validators\test;

use jp3cki\yii2\validators\TwitterAccountValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;
use jp3cki\yii2\validators\testsrc\models\ModelForTwitterAccountValidator as TestModel;

class TwitterAccountValidatorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    public function testDefaultMessage()
    {
        $o = new Target();
        $o->init();
        $this->assertNotEmpty($o->message);
    }

    public function testMessageOverwrite()
    {
        $o = new Target();
        $o->message = 'hoge';
        $o->init();
        $this->assertEquals('hoge', $o->message);
        $o->message = 'fuga';
        $this->assertEquals('fuga', $o->message);
    }

    public function testDefaultNonUsernames()
    {
        $o = new Target();
        $o->init();
        $this->assertTrue(is_array($o->nonUsernamePaths));
        $this->assertGreaterThan(0, is_array($o->nonUsernamePaths));
    }

    public function testNonUsernamesOverwrite()
    {
        $o = new Target();
        $o->nonUsernamePaths = [];
        $o->init();
        $this->assertTrue(is_array($o->nonUsernamePaths));
        $this->assertEquals(0, count($o->nonUsernamePaths));
    }

    /**
     * @dataProvider screenNamesProvider
     */
    public function testValidate($expect, $screen_name)
    {
        $o = new Target();
        $o->init();
        $this->assertEquals($expect, $o->validate($screen_name));
    }

    /**
     * @dataProvider screenNamesProvider
     */
    public function testValidateAttribute($expect, $screen_name)
    {
        $o = new TestModel();
        $o->init();
        $o->value = $screen_name;
        $this->assertEquals($expect, $o->validate());
    }

    public function screenNamesProvider()
    {
        return [
            [false, ''],
            [true, '1'],
            [true, 'a'],
            [true, 'A'],
            [true, '_'],
            [false, '-'],
            [true, 'fetus_hina'],
            [true, 'FETUS_HINA'],
            [false, 'fetus-hina'], // hypen
            [false, '1234567890123456'], // too long
            [false, 'mentions'], // reserved
        ];
    }
}
