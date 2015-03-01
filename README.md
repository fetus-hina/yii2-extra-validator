yii2-extra-validator
====================

[![License](https://poser.pugx.org/jp3cki/yii2-extra-validator/license.svg)](https://packagist.org/packages/jp3cki/yii2-extra-validator)
[![Latest Stable Version](https://poser.pugx.org/jp3cki/yii2-extra-validator/v/stable.svg)](https://packagist.org/packages/jp3cki/yii2-extra-validator)
[![Build Status](https://travis-ci.org/fetus-hina/yii2-extra-validator.svg?branch=master)](https://travis-ci.org/fetus-hina/yii2-extra-validator)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/fetus-hina/yii2-extra-validator/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/fetus-hina/yii2-extra-validator/?branch=master)
[![Code Climate](https://codeclimate.com/github/fetus-hina/yii2-extra-validator/badges/gpa.svg)](https://codeclimate.com/github/fetus-hina/yii2-extra-validator)
[![Test Coverage](https://codeclimate.com/github/fetus-hina/yii2-extra-validator/badges/coverage.svg)](https://codeclimate.com/github/fetus-hina/yii2-extra-validator)

Requirements
------------

- PHP 5.4.0 or later
- Yii framework 2.0.x(?)

Install
-------

1. Set up [Composer](https://getcomposer.org/), the de facto standard package manager.
2. Set up your new Yii app if needed.
3. `php composer.phar require jp3cki/yii2-extra-validator`

Usage
-----

This package includes these validators:
- [AvailableUrlValidator](#availableurlvalidator)
- [IdnToPunycodeFilterValidator](#idntopunycodefiltervalidator)
- [ReCaptchaValidator](#recaptchavalidator)
- [StrictUrlValidator](#stricturlvalidator)
- [TwitterAccountValidator](#twitteraccountvalidator)


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
```
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
