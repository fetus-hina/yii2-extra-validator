<?php

declare(strict_types=1);

namespace jp3cki\yii2\validators\test;

use Yii;
use jp3cki\yii2\validators\AvailableUrlValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;
use jp3cki\yii2\validators\testsrc\models\ModelForAvailableUrlValidator as TestModel;

use function count;

/**
 * @group url
 */
class AvailableUrlValidatorTest extends TestCase
{
    public function setUp(): void
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
        $o = new Target();
        $this->assertTrue($o->validate('https://github.com/fetus-hina/yii2-extra-validator'));

        $o = new TestModel();
        $o->init();
        $o->value = 'https://github.com/fetus-hina/yii2-extra-validator';
        $this->assertTrue($o->validate());
    }

    public function testFailure()
    {
        $o = new Target();
        $error = null;
        $this->assertFalse($o->validate('https://unknownhost.fetus.jp/', $error));
        $this->assertNotEmpty($error);

        $o = new TestModel();
        $o->init();
        $o->value = 'https://unknownhost.fetus.jp/';
        $this->assertFalse($o->validate());
        $this->assertGreaterThan(0, count($o->errors['value']));
        $this->assertNotEmpty($o->errors['value']);
    }

    public function testMessageJapanese()
    {
        Yii::$app->language = 'ja-JP';
        $o = new Target();
        $error = null;
        $this->assertFalse($o->validate('https://unknownhost.fetus.jp/', $error));
        $this->assertNotEmpty($error);
        $this->assertMatchesRegularExpression('/[ぁ-ゟ゠-ヿ]/u', $error); // ひらがな・カタカナを含む

        $o = new TestModel();
        $o->init();
        $o->value = 'https://unknownhost.fetus.jp/';
        $this->assertFalse($o->validate());
        $this->assertGreaterThan(0, count($o->errors['value']));
        $this->assertNotEmpty($o->errors['value']);
        $this->assertMatchesRegularExpression('/[ぁ-ゟ゠-ヿ]/u', $o->errors['value'][0]); // ひらがな・カタカナを含む
    }
}
