<?php
namespace jp3cki\yii2\validators\test;

use jp3cki\yii2\validators\ReCaptchaValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;
use jp3cki\yii2\validators\testsrc\models\ModelForReCaptchaValidator as TestModel;

class ReCaptchaValidatorTest extends TestCase
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

    public function testSuccessful()
    {
        $o = new TestModel();
        $o->init();
        $o->scenario = 'successfulTest';
        $o->value = 'TEST';
        $this->assertTrue($o->validate());
    }

    public function testFailure()
    {
        $o = new TestModel();
        $o->init();
        $o->scenario = 'failureTest';
        $o->value = 'TEST';
        $this->assertFalse($o->validate());
    }

    public function testNetworkFailure()
    {
        $o = new TestModel();
        $o->init();
        $o->scenario = 'networkFailureTest';
        $o->value = 'TEST';
        $this->assertFalse($o->validate());
    }
}
