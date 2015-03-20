<?php
namespace jp3cki\yii2\validators\test;

use Yii;
use yii\base\DynamicModel;
use jp3cki\yii2\validators\ConvertCharacterWidthFilterValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;

/**
 * @group japanese
 */
class ConvertCharacterWidthFilterValidatorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFilter($expected, $input, $charset)
    {
        $model = DynamicModel::validateData(
            ['value' => $input],
            [[['value'], Target::className(), 'charset' => $charset]]
        );
        $this->assertEquals($expected, $model->value);
    }

    public function dataProvider()
    {
        $set = [
            [' ', '　', null],
            ['A', 'Ａ', null],
            ['!', '！', null],
            ['ア', 'ｱ', null],
            ['ガ', 'ｶﾞ', null],
        ];
        $convCharset = function (array $arrayArray, $charset) {
            foreach ($arrayArray as &$array) {
                $array[0] = mb_convert_encoding($array[0], $charset, 'UTF-8');
                $array[1] = mb_convert_encoding($array[1], $charset, 'UTF-8');
                $array[2] = $charset;
            }
            return $arrayArray;
        };
        return array_merge(
            $set,
            $convCharset($set, 'UTF-8'),
            $convCharset($set, 'CP932'),
            $convCharset($set, 'EUCJP-WIN')
        );
    }
}
