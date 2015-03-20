<?php
namespace jp3cki\yii2\validators\test;

use Yii;
use yii\base\DynamicModel;
use jp3cki\yii2\validators\JpPhoneNumberValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;

/**
 * @group phone
 */
class JpPhoneNumberValidatorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testValidator($expected, $types, $hyphen, $value)
    {
        $o = new Target();
        $o->types = $types;
        $o->hyphen = $hyphen;
        $o->init();
        $this->assertEquals($expected, $o->validate($value));
    }

    /**
     * @dataProvider dataProvider
     */
    public function testWithModel($expected, $types, $hyphen, $value)
    {
        $model = DynamicModel::validateData(
            ['value' => $value],
            [
                [['value'], Target::className(), 'types' => $types, 'hyphen' => $hyphen],
            ]
        );
        $this->assertEquals($expected, !$model->hasErrors());
    }

    public function dataProvider()
    {
        return [
            [true, Target::FLAG_FREE_DIAL, null, '0120123456'],
            [true, Target::FLAG_FREE_DIAL, null, '0120-123-456'],
            [true, Target::FLAG_FREE_DIAL, null, '0120-12-3456'],
            [true, Target::FLAG_FREE_ACCESS, null, '08009876543'],
            [true, Target::FLAG_FREE_ACCESS, null, '0800-987-6543'],
            [true, Target::FLAG_IP_PHONE, null, '05010091234'],
            [true, Target::FLAG_IP_PHONE, null, '050-1009-1234'],
            [true, Target::FLAG_LANDLINE, null, '0352535111'],
            [true, Target::FLAG_LANDLINE, null, '0112001234'],
            [true, Target::FLAG_LANDLINE, null, '0123201234'],
            [true, Target::FLAG_LANDLINE, null, '0126721234'],
            [true, Target::FLAG_LANDLINE, null, '03-5253-5111'],
            [true, Target::FLAG_LANDLINE, null, '011-200-1234'],
            [true, Target::FLAG_LANDLINE, null, '0123-20-1234'],
            [true, Target::FLAG_LANDLINE, null, '01267-2-1234'],
            [true, Target::FLAG_MOBILE, null, '09010091234'],
            [true, Target::FLAG_MOBILE, null, '090-1009-1234'],
            [true, Target::FLAG_MOBILE, null, '08010091234'],
            [true, Target::FLAG_MOBILE, null, '080-1009-1234'],
            [true, Target::FLAG_MOBILE, null, '07050191234'],
            [true, Target::FLAG_MOBILE, null, '070-5019-1234'],
            [true, Target::FLAG_NAV_DIAL, null, '0570000123'],
            [true, Target::FLAG_NAV_DIAL, null, '0570-000-123'],
            [true, Target::FLAG_NAV_DIAL, null, '0570-00-0123'],
            [true, Target::FLAG_PAGER, null, '02046091234'],
            [true, Target::FLAG_PAGER, null, '020-4609-1234'],
        ];
    }
}
