<?php
namespace jp3cki\yii2\validators\test;

use Yii;
use jp3cki\yii2\validators\IdnToPunycodeFilterValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;
use jp3cki\yii2\validators\testsrc\models\ModelForIdnToPunycodeFilterValidator as TestModel;

class IdnToPunycodeFilterValidatorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testFilter($idn, $ascii)
    {
        $o = new TestModel();
        $o->init();
        $o->value = $idn;
        $o->validate();
        $this->assertEquals($ascii, $o->value);
    }


    public function dataProvider()
    {
        $set = [
            ['example.com', 'example.com'],
            ['täst.de', 'xn--tst-qla.de'],
            ['ドメイン名例.JP', 'xn--eckwd4c7cu47r2wf.jp'],
            ['テスト.example.com', 'xn--zckzah.example.com'],
            ['テスト.ドメイン名例.jp', 'xn--zckzah.xn--eckwd4c7cu47r2wf.jp'],
            ['ドメイン名例。JP', 'xn--eckwd4c7cu47r2wf.jp'],
        ];
        return array_merge(
            // example.com
            $set,
            // http://example.com
            array_map(function ($pair) {
                return ['http://' . $pair[0], 'http://' . $pair[1]];
            }, $set),
            // https://example.com
            array_map(function ($pair) {
                return ['https://' . $pair[0], 'https://' . $pair[1]];
            }, $set),
            // example.com/foo
            array_map(function ($pair) {
                return [$pair[0] . '/foo', $pair[1] . '/foo'];
            }, $set),
            // //example.com/
            array_map(function ($pair) {
                return ['//' . $pair[0] . '/', '//' . $pair[1] . '/'];
            }, $set)
        );
    }
}
