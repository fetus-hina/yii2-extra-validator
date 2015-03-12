<?php
namespace jp3cki\yii2\validators\test;

use Yii;
use yii\base\DynamicModel;
use jp3cki\yii2\validators\ZenginNameValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;

class ZenginNameValidatorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testValidator($expected, $value, $charset)
    {
        $o = new Target();
        $o->charset = $charset;
        $o->init();
        $this->assertEquals($expected, $o->validate($value));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testWithModel($expected, $value, $charset)
    {
        $model = DynamicModel::validateData(
            ['value' => $value],
            [
                [['value'], Target::className(), 'charset' => $charset],
            ]
        );
        $this->assertEquals($expected, !$model->hasErrors());
    }

    public function dataProvider()
    {
        $set = [
            [true,  'ｱｲｻﾞﾜ ﾋﾅ', null],
            [false, 'アイザワヒナ', null],
            [true,  'ｸﾛｴ ﾙﾒ-ﾙ', null], // ハイフン
            [false, 'ｸﾛｴ ﾙﾒｰﾙ', null], // 長音は使えない
            [true,  'ｶ)ﾆﾎﾝ', null],
            [true,  'ﾆﾎﾝ(ｶ)ｼﾝｼﾞﾕｸ(ｴｲ', null],
            [true,  'ﾆﾎﾝ(ｶ', null],
            [true,  'ﾈﾂﾄ.ｾﾝﾀ-', null],
            [false, 'ﾈﾂﾄ･ｾﾝﾀ-', null],
            [false, 'ﾈｯﾄ.ｾﾝﾀ-', null],
            [true,  'ABCDEFG', null],
            [false, 'abcdefg', null],
            [true,  '1234567', null],
            [true,  '\\,.｢｣()-/', null],
            [false, '!"#$%&', null],
        ];
        $convCharset = function (array $arrayArray, $charset) {
            foreach ($arrayArray as &$array) {
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
