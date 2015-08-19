yii2-extra-validator
====================

[![License](https://poser.pugx.org/jp3cki/yii2-extra-validator/license.svg)](https://packagist.org/packages/jp3cki/yii2-extra-validator)
[![Latest Stable Version](https://poser.pugx.org/jp3cki/yii2-extra-validator/v/stable.svg)](https://packagist.org/packages/jp3cki/yii2-extra-validator)
[![Build Status](https://travis-ci.org/fetus-hina/yii2-extra-validator.svg?branch=master)](https://travis-ci.org/fetus-hina/yii2-extra-validator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fetus-hina/yii2-extra-validator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fetus-hina/yii2-extra-validator/?branch=master)
[![Code Climate](https://codeclimate.com/github/fetus-hina/yii2-extra-validator/badges/gpa.svg)](https://codeclimate.com/github/fetus-hina/yii2-extra-validator)
[![Test Coverage](https://codeclimate.com/github/fetus-hina/yii2-extra-validator/badges/coverage.svg)](https://codeclimate.com/github/fetus-hina/yii2-extra-validator)
[![Dependency Status](https://www.versioneye.com/user/projects/55d4d6356dbe17001b000013/badge.svg?style=flat)](https://www.versioneye.com/user/projects/55d4d6356dbe17001b000013)

Requirements
------------

- PHP 5.4.0 or later
  - Works on PHP 7.0 and HHVM!
- Yii framework 2.0
- Some php extensions is required:
  - PCRE extension (also required by Yii)
  - mbstring extension (also required by Yii)
  - intl extension (also required by Yii in some action)

Install
-------

1. Set up [Composer](https://getcomposer.org/), the de facto standard package manager.
2. Set up your new Yii app if needed.
3. `php composer.phar require jp3cki/yii2-extra-validator`

Usage
-----

This package includes these validators:
- [AvailableUrlValidator](#availableurlvalidator)
- [ConvertCharacterWidthFilterValidator](#convertcharacterwidthfiltervalidator)
- [HiraganaValidator](#hiraganavalidator)
- [IdnToPunycodeFilterValidator](#idntopunycodefiltervalidator)
- [KatakanaValidator](#katakanavalidator)
- [ReCaptchaValidator](#recaptchavalidator)
- [StrictUrlValidator](#stricturlvalidator)
- [TwitterAccountValidator](#twitteraccountvalidator)
- [ZenginNameFilterValidator](#zenginnamefiltervalidator)
- [ZenginNameValidator](#zenginnamevalidator)

### AvailableUrlValidator ###

`AvailableUrlValidator` will check that the URL is accessible.

Validation will failed if remote server returns 4xx(client error) or 5xx(server error).

Model class example:
```php
namespace app\models;

use yii\base\Model;
use jp3cki\yii2\validators\AvailableUrlValidator;
// use jp3cki\yii2\validators\StrictUrlValidator;

class YourCustomForm extends Model
{
    public $url;

    public function rules()
    {
        return [
            [['url'], 'url', 'enableIDN' => true], // <- Core validator
            // [['url'], StrictUrlValidator::className(), 'enableIDN' => true]
            [['url'], AvailableUrlValidator::className()],
        ];
    }
}
```


### ConvertCharacterWidthFilterValidator ###

`ConvertCharacterWidthFilterValidator` is a filter validator that provides normalization of character width.

This validator may useful for Japanese input.

このフィルタバリデータを利用すると、半角カタカナの入力を全角に変換したり、全角英数を半角英数に変換したり、カタカナをひらがなに変換したりできます。

[HiraganaValidator](#hiraganavalidator)や[KatakanaValidator](#katakanavalidator)と組み合わせて使用すると便利かもしれません。

Model class example:
```php
namespace app\models;

use yii\base\Model;
use jp3cki\yii2\validators\ConvertCharacterWidthFilterValidator;

class YourCustomForm extends Model
{
    public $value;

    public function rules()
    {
        return [
            [['value'], ConvertCharacterWidthFilterValidator::className(),
                'option' => 'asKV', // mb_convert_kana() 関数の変換書式を指定します。デフォルトは asKV です。
                'charset' => 'UTF-8', // 必要であれば文字コードを指定します。デフォルトは Yii::app()->charset で、通常 UTF-8 です。
            ],
        ];
    }
}
```


### HiraganaValidator ###

`HiraganaValidator` validates value that input is [hiragana](http://en.wikipedia.org/wiki/Hiragana)-only string.

このバリデータは入力がひらがなのみで構成されていることを検証します。名前のふりがな入力等に利用できます。

カタカナの検証を行いたい場合は[KatakanaValidator](#katakanavalidator)を使用します。

Model class example:
```php
namespace app\models;

use yii\base\Model;
use jp3cki\yii2\validators\HiraganaValidator;

class YourCustomForm extends Model
{
    public $value;

    public function rules()
    {
        return [
            [['value'], HiraganaValidator::className(),
                'acceptSpace' => false,  // スペース（半角・全角）を許容する場合は true を設定します。デフォルトは false です。
                'charset' => 'UTF-8', // 必要であれば文字コードを指定します。デフォルトは Yii::app()->charset で、通常 UTF-8 です。
            ],
        ];
    }
}
```


### IdnToPunycodeFilterValidator ###

`IdnToPunycodeFilterValidator` is a filter validator that provides convert IDN to Punycoded domain name.

This validator may useful when you store URL to the database in ASCII charset.

Model class example:
```php
namespace app\models;

use yii\base\Model;
use jp3cki\yii2\validators\IdnToPunycodeFilterValidator;
// use jp3cki\yii2\validators\StrictUrlValidator;

class YourCustomForm extends Model
{
    public $url;

    public function rules()
    {
        return [
            [['url'], 'url', 'enableIDN' => true], // <- Core validator
            // [['url'], StrictUrlValidator::className(), 'enableIDN' => true]
            [['url'], IdnToPunycodeFilterValidator::className()],
        ];
    }
}
```

Controller class example:
```php
public function actionUpdate()
{
    $model = new YourCustomForm();
    $model->url = 'http://ドメイン名例.JP/'; // user input
    if ($model->validate()) {
        // $model->url is now 'http://xn--eckwd4c7cu47r2wf.jp/'

        // $dbModel->url = $model->url;
        // $dbModel->save();
    }
}
```


### KatakanaValidator ###

`KatakanaValidator` validates value that input is [katakana](http://en.wikipedia.org/wiki/Katakana)-only string.

このバリデータは入力がカタカナのみで構成されていることを検証します。名前のフリガナ入力等に利用できます。

ひらがなの検証を行いたい場合は[HiraganaValidator](#hiraganavalidator)を使用します。

Model class example:
```php
namespace app\models;

use yii\base\Model;
use jp3cki\yii2\validators\KatakanaValidator;

class YourCustomForm extends Model
{
    public $value;

    public function rules()
    {
        return [
            [['value'], KatakanaValidator::className(),
                'acceptSpace' => false,  // スペース（半角・全角）を許容する場合は true を設定します。デフォルトは false です。
                'charset' => 'UTF-8', // 必要であれば文字コードを指定します。デフォルトは Yii::app()->charset で、通常 UTF-8 です。
            ],
        ];
    }
}
```


### ReCaptchaValidator ###

`ReCaptchaValidator` validates reCAPTCHA (API ver.2) input.

First, you must visit [reCAPTCHA website](https://www.google.com/recaptcha/intro/index.html) and create your keys.

After reCAPTCHA registration, you can get `Site key` and `Secret key`.

Open `@app/config/params.php` file and add these keys like below:
```php
<?php
return [
    'adminEmail' => 'admin@example.com',
    'recaptchaSiteKey' => 'YOUR-SITE-KEY', // <- This!
    'recaptchaSecret' => 'YOUR-SECRET-KEY', // <- and this!
];
```

In the HTML output phase, you can use the HTML snippet in the website of reCAPTCHA.

In validation phase, you can set `$_POST['g-recaptcha-response']` parameter to our validator and verification.

```php
<?php
namespace app\models;

use Yii;
use yii\base\Model;
use jp3cki\yii2\validators\ReCaptchaValidator;

class ReCaptchaForm extends Model
{
    public $recaptcha;

    public function rules()
    {
        return [
            [['recaptcha'], ReCaptchaValidator::className(),
                'secret' => Yii::$app->params['recaptchaSecret']], // <- set SECRET KEY to the validator
        ];
    }

    public function attributeLabels()
    {
        return [
            'recaptcha' => 'reCAPTCHA',
        ];
    }
}
```

```php
// (in Controller class)
public function actionUpdate()
{
    $request = Yii::$app->request;

    $form = new ReCaptchaForm();
    $form->recaptcha = $request->post('g-recaptcha-response'); // <- set g-recptcha-response to the validator
    if ($form->validate()) {
        // ok
    } else {
        // failed
    }
}
```


### StrictUrlValidator ###

`StrictUrlValidator` validates URL that checks strictly than core validator implementation.

Input                   | UrlValidator(core) | StrictUrlValidator(this)
------------------------|--------------------|-------------------------
`http://example.com/`   | valid              | valid
`http://example.com/あ` | valid              | invalid
`http://example.comあ/` | valid              | invalid

Model class example:
```php
namespace app\models;

use yii\base\Model;
use jp3cki\yii2\validators\StrictUrlValidator;

class YourCustomForm extends Model
{
    public $url;

    public function rules()
    {
        return [
            [['url'], StrictUrlValidator::className(), 'enableIDN' => true]
        ];
    }
}
```


### TwitterAccountValidator ###

`TwitterAccountValidator` validates Twitter's `@id` name(aka screen name).

By default, our validator repels blacklisted account name like `mentions`.

Model class example:
```php
<?php
namespace app\models;

use Yii;
use yii\base\Model;
use jp3cki\yii2\validators\TwitterAccountValidator;

class YourCustomForm extends Model
{
    public $screenName;

    public function rules()
    {
        return [
            [['screenName'], TwitterAccountValidator::className()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'screenName' => 'Screen Name',
        ];
    }
}
```

Currently, we do not support client-side(JavaScript) validation.


### ZenginNameFilterValidator ###
### ZenginNameValidator ###

These validators are useful if you are dealing with a bank account for the Japanese app.

`ZenginNameFilterValidator` は入力された文字列を（自動判断できる範囲で）全銀用の文字列に変換します。

実際の利用では、口座名義などにフィルタをかけ、実際のバリデータである `ZenginNameValidator` へデータへ引き継ぐことになります。

`ZenginNameValidator` は入力された文字列が全銀用の文字列として妥当か検査します。
このバリデータは例えば全角カタカナを受け付けないので、 `ZenginNameFilterValidator` を事前に通して入力の補助とできます。
（半角カタカナで入力を強制するのは非人道的です。また、長音の代わりにハイフンを使用する必要があるなど人間が徹底して守るにはつらいです）

Model class example:
```php
<?php
namespace app\models;

use Yii;
use yii\base\Model;
use jp3cki\yii2\validators\ZenginNameFilterValidator;
use jp3cki\yii2\validators\ZenginNameValidator;

class YourCustomForm extends Model
{
    public $accountName; // 銀行口座名義

    public function rules()
    {
        return [
            // [['accountName'], 'required'],
            [['accountName'], ZenginNameFilterValidator::className()],
            [['accountName'], ZenginNameValidator::className()],
            // [['accountName'], 'string', 'max' => 30],
        ];
    }

    public function attributeLabels()
    {
        return [
            'accountName' => '銀行口座名義',
        ];
    }
}
```


License
-------

[The MIT License](https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE).

```
The MIT License (MIT)

Copyright (c) 2015 AIZAWA Hina <hina@bouhime.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

Contributing
------------

Patches and/or report issues are welcome.

- Please create new branch for each issue or feature. (should not work in master branch)
- Please write and run test. `$ make test`
- Coding style is PSR-2.
    - Please run check-style for static code analysis and coding rule checking. `$ make check-style`
- Please clean up commits.
- Please create new pull-request for each issue or feature.
- Please gazing the results of Travis-CI and other hooks.
- Please use Japanese or *very simple* English to create new pull-request or issue.

I strongly hope rewrite my poor English.
