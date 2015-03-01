<?php
namespace jp3cki\yii2\validators\test;

use Yii;
use jp3cki\yii2\validators\StrictUrlValidator as Target;
use jp3cki\yii2\validators\testsrc\TestCase;

class StrictUrlValidatorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockApplication();
    }

    /**
     * @dataProvider dataProvider
     */
    public function testValidator($expect, $idn, $url)
    {
        $o = new Target();
        $o->init();
        $o->enableIDN = !!$idn;
        $this->assertEquals($expect, $o->validate($url));
    }


    public function dataProvider()
    {
        return [
            [true,  false, 'http://example.com/'], // basic
            [true,  true,  'http://example.com/'], // basic
            [true,  false, 'https://example.com/'], // basic
            [true,  true,  'https://example.com/'], //basic
            [true,  false, 'http://user:pass@example.com:8080/path/to/resource?query#f'], // basic
            [false, true,  'ttp://example.com/'], // invalid scheme
            [false, true,  'ftp://example.com/'], // ftp is not valid
            [true,  true,  'http://xn--eckwd4c7cu47r2wf.jp/'], // punycoded
            [true,  true,  'http://ドメイン例。JP/'], // idn
            [false, false, 'http://ドメイン例。JP/'], // idn is disable
            [false, true,  'http://example.com/あいうえお'], // UrlValidator passes
        ];
    }
}
