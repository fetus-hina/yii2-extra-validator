<?php

declare(strict_types=1);

namespace jp3cki\yii2\validators\test;

use Override;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use jp3cki\yii2\validators\ZenginNameValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;
use yii\base\DynamicModel;

use function array_merge;
use function mb_convert_encoding;

#[Group('zengin')]
class ZenginNameValidatorTest extends TestCase
{
    #[Override]
    public function setUp(): void
    {
        parent::setUp();
        $this->mockApplication();
    }

    #[DataProvider('dataProvider')]
    public function testValidator($expected, $value, $charset)
    {
        $o = new Target();
        $o->charset = $charset;
        $o->init();
        $this->assertEquals($expected, $o->validate($value));
    }

    #[DataProvider('dataProvider')]
    public function testWithModel($expected, $value, $charset)
    {
        $model = DynamicModel::validateData(
            ['value' => $value],
            [
                [['value'], Target::class, 'charset' => $charset],
            ],
        );
        $this->assertEquals($expected, !$model->hasErrors());
    }

    public static function dataProvider()
    {
        $set = [
            [true, 'ｱｲｻﾞﾜ ﾋﾅ', null],
            [false, 'アイザワヒナ', null],
            [true, 'ｸﾛｴ ﾙﾒ-ﾙ', null], // ハイフン
            [false, 'ｸﾛｴ ﾙﾒｰﾙ', null], // 長音は使えない
            [true, 'ｶ)ﾆﾎﾝ', null],
            [true, 'ﾆﾎﾝ(ｶ)ｼﾝｼﾞﾕｸ(ｴｲ', null],
            [true, 'ﾆﾎﾝ(ｶ', null],
            [true, 'ﾈﾂﾄ.ｾﾝﾀ-', null],
            [false, 'ﾈﾂﾄ･ｾﾝﾀ-', null],
            [false, 'ﾈｯﾄ.ｾﾝﾀ-', null],
            [true, 'ABCDEFG', null],
            [false, 'abcdefg', null],
            [true, '1234567', null],
            [true, '\\,.｢｣()-/', null],
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
            $convCharset($set, 'EUCJP-WIN'),
        );
    }
}
