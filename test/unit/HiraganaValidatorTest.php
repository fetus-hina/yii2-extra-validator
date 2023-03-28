<?php

declare(strict_types=1);

namespace jp3cki\yii2\validators\test;

use jp3cki\yii2\validators\HiraganaValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;
use yii\base\DynamicModel;

use function array_merge;
use function mb_convert_encoding;

/**
 * @group japanese
 */
class HiraganaValidatorTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->mockApplication();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testValidator($expected, $acceptSpace, $value, $charset)
    {
        $o = new Target();
        $o->acceptSpace = $acceptSpace;
        $o->charset = $charset;
        $o->init();
        $this->assertEquals($expected, $o->validate($value));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testWithModel($expected, $acceptSpace, $value, $charset)
    {
        $model = DynamicModel::validateData(
            ['value' => $value],
            [
                [['value'], Target::className(),
                    'acceptSpace' => $acceptSpace,
                    'charset' => $charset,
                ],
            ],
        );
        $this->assertEquals($expected, !$model->hasErrors());
    }

    public function dataProvider()
    {
        $set = [
            [true, true, 'あいざわひな', null],
            [true, false, 'あいざわひな', null],
            [true, true, 'あいざわ　ひな', null],
            [true, true, 'あいざわ ひな', null],
            [false, false, 'あいざわ　ひな', null], // <- スペース不許可
            [false, false, 'あいざわ ひな', null], // <- スペース不許可
            [false, true, '相沢陽菜', null],
            [false, false, '相沢陽菜', null],
            [false, true, '相沢ひな', null],
            [false, false, '相沢ひな', null],
            [false, true, 'アイザワヒナ', null],
            [false, false, 'アイザワヒナ', null],
        ];
        $convCharset = function (array $arrayArray, $charset) {
            foreach ($arrayArray as &$array) {
                $array[2] = mb_convert_encoding($array[2], $charset, 'UTF-8');
                $array[3] = $charset;
            }
            return $arrayArray;
        };
        return array_merge(
            $set,
            $convCharset($set, 'UTF-8'),
            $convCharset($set, 'CP932'),
            $convCharset($set, 'EUCJP-WIN'),
        );
    }
}
