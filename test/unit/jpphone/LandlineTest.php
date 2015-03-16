<?php
namespace jp3cki\yii2\validators\test\jpphone;

use Yii;
use yii\base\DynamicModel;
use jp3cki\yii2\validators\JpPhoneNumberValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;

/**
 * @runTestsInSeparateProcesses
 */
class LandlineTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    public function testModel()
    {
        $model = DynamicModel::validateData(
            ['value' => '03-5253-5111'],
            [
                [['value'], Target::className(),
                    'types' => Target::FLAG_LANDLINE,
                    'hyphen' => null,
                ],
            ]
        );
        $this->assertFalse($model->hasErrors());
    }

    /**
     * @dataProvider validNumberProvider
     */
    public function testLandline($number, $number2)
    {
        $o = new Target();
        $o->types = Target::FLAG_LANDLINE;
        $o->hyphen = null;
        $this->assertTrue($o->validate($number));
        $this->assertTrue($o->validate($number2));

        $o->hyphen = false;
        $this->assertFalse($o->validate($number));
        $this->assertTrue($o->validate($number2));

        $o->hyphen = true;
        $this->assertTrue($o->validate($number));
        $this->assertFalse($o->validate($number2));
    }

    public function validNumberProvider()
    {
        return [
            ['03-5253-5111', '0352535111'],
            ['011-200-1234', '0112001234'],
            ['0123-20-1234', '0123201234'],
            ['01267-2-1234', '0126721234'],
        ];
    }

    // ハイフン区切りの局番がおかしなもの
    public function testInvalidHyphen()
    {
        $o = new Target();
        $o->types = Target::FLAG_LANDLINE;
        $o->hyphen = null;
        $this->assertTrue($o->validate('0112001234')); // ハイフンがなければ正しい
        $this->assertFalse($o->validate('0112-00-1234'));
    }

    // フラグが動作することを確認
    public function testFlag()
    {
        $o = new Target();
        foreach (['03-5253-5111'] as $number) {
            $o->types = Target::FLAG_LANDLINE;
            $this->assertTrue($o->validate($number));
            $o->types = 0;
            $this->assertFalse($o->validate($number));
        }
    }
}
