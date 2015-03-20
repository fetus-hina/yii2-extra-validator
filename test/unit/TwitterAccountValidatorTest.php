<?php
namespace jp3cki\yii2\validators\test;

use Yii;
use jp3cki\yii2\validators\TwitterAccountValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;
use jp3cki\yii2\validators\testsrc\models\ModelForTwitterAccountValidator as TestModel;

/**
 * @group sns
 */
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
    public function testValidate($expect, $screenName)
    {
        $o = new Target();
        $o->init();
        $error = null;
        $this->assertEquals($expect, $o->validate($screenName, $error));
        if ($expect === false) {
            $this->assertRegExp('/^[\x20-\x7e]+$/', $error);
        }
    }

    /**
     * @dataProvider screenNamesProvider
     */
    public function testValidateJa($expect, $screenName)
    {
        Yii::$app->language = 'ja-JP';
        $o = new Target();
        $o->init();
        $error = null;
        $this->assertEquals($expect, $o->validate($screenName, $error));
        if ($expect === false) {
            $this->assertRegExp('/[ぁ-ゟ゠-ヿ]/u', $error); // ひらがな・カタカナを含む
        }
    }

    /**
     * @dataProvider screenNamesProvider
     */
    public function testValidateAttribute($expect, $screenName)
    {
        $o = new TestModel();
        $o->init();
        $o->value = $screenName;
        $this->assertEquals($expect, $o->validate());
        if ($expect === false) {
            $this->assertRegExp('/^[\x20-\x7e]+$/', $o->errors['value'][0]);
        }
    }

    /**
     * @dataProvider screenNamesProvider
     */
    public function testValidateAttributeJa($expect, $screenName)
    {
        Yii::$app->language = 'ja-JP';
        $o = new TestModel();
        $o->init();
        $o->value = $screenName;
        $this->assertEquals($expect, $o->validate());
        if ($expect === false) {
            $this->assertRegExp('/[ぁ-ゟ゠-ヿ]/u', $o->errors['value'][0]); // ひらがな・カタカナを含む
        }
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
