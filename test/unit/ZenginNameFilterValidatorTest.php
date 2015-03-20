<?php
namespace jp3cki\yii2\validators\test;

use Yii;
use yii\base\DynamicModel;
use jp3cki\yii2\validators\ZenginNameFilterValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;

/**
 * @group zengin
 */
class ZenginNameFilterValidatorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFilter($expected, $value, $charset)
    {
        $model = DynamicModel::validateData(
            ['value' => $value],
            [
                [['value'], Target::className(), 'charset' => $charset],
            ]
        );
        $this->assertEquals($expected, $model->value);
    }


    public function dataProvider()
    {
        $set = [
            ['ｱｲｻﾞﾜ ﾋﾅ', 'アイザワ　ヒナ', null],
            ['ｱｲｻﾞﾜ ﾋﾅ', 'あいざわ　ひな', null],
            ['ｼﾞﾔﾊﾟﾝﾈﾂﾄｷﾞﾝｺｳ', 'ジャパンネットギンコウ', null],
            ['ﾈﾂﾄ.ｾﾝﾀ-', 'ネット・センター', null],
            ['ABCDEFG', 'ＡＢＣＤＥＦＧ', null],
            ['ABCDEFG', 'ａｂｃｄｅｆｇ', null],
            ['0123456', '０１２３４５６', null],
            ['\\', '￥', null],
            [',.｢｣()/', '，．「」（）／', null],
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
