<?php

declare(strict_types=1);

namespace jp3cki\yii2\validators\test;

use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use Yii;
use jp3cki\yii2\validators\TwitterAccountValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;
use jp3cki\yii2\validators\testsrc\models\ModelForTwitterAccountValidator as TestModel;

use function count;
use function is_array;

#[Group('sns')]
class TwitterAccountValidatorTest extends TestCase
{
    #[Override]
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

    #[DataProvider('screenNamesProvider')]
    public function testValidate($expect, $screenName)
    {
        $o = new Target();
        $o->init();
        $error = null;
        $this->assertEquals($expect, $o->validate($screenName, $error));
        if ($expect === false) {
            $this->assertMatchesRegularExpression('/^[\x20-\x7e]+$/', $error);
        }
    }

    #[DataProvider('screenNamesProvider')]
    public function testValidateJa($expect, $screenName)
    {
        Yii::$app->language = 'ja-JP';
        $o = new Target();
        $o->init();
        $error = null;
        $this->assertEquals($expect, $o->validate($screenName, $error));
        if ($expect === false) {
            $this->assertMatchesRegularExpression('/[ぁ-ゟ゠-ヿ]/u', $error); // ひらがな・カタカナを含む
        }
    }

    #[DataProvider('screenNamesProvider')]
    public function testValidateAttribute($expect, $screenName)
    {
        $o = new TestModel();
        $o->init();
        $o->value = $screenName;
        $this->assertEquals($expect, $o->validate());
        if ($expect === false) {
            $this->assertMatchesRegularExpression('/^[\x20-\x7e]+$/', $o->errors['value'][0]);
        }
    }

    #[DataProvider('screenNamesProvider')]
    public function testValidateAttributeJa($expect, $screenName)
    {
        Yii::$app->language = 'ja-JP';
        $o = new TestModel();
        $o->init();
        $o->value = $screenName;
        $this->assertEquals($expect, $o->validate());
        if ($expect === false) {
            $this->assertMatchesRegularExpression('/[ぁ-ゟ゠-ヿ]/u', $o->errors['value'][0]); // ひらがな・カタカナを含む
        }
    }

    public static function screenNamesProvider()
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
