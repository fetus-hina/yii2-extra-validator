<?php

declare(strict_types=1);

namespace jp3cki\yii2\validators\testsrc\models;

use jp3cki\yii2\validators\ReCaptchaValidator;
use yii\base\Model;

class ModelForReCaptchaValidator extends Model
{
    public $value;

    public function rules()
    {
        return [
            [['value'], ReCaptchaValidator::className(), 'on' => 'successfulTest',
                'endPoint' => 'https://mock.fetus.jp/recaptcha/api/siteverify.success.json',
                'secret' => 'SECRET KEY',
                'remoteIp' => '127.0.0.2',
            ],
            [['value'], ReCaptchaValidator::className(), 'on' => 'failureTest',
                'endPoint' => 'https://mock.fetus.jp/recaptcha/api/siteverify.error.json',
                'secret' => 'SECRET KEY',
                'remoteIp' => '127.0.0.2',
            ],
            [['value'], ReCaptchaValidator::className(), 'on' => 'networkFailureTest',
                'endPoint' => 'https://unknownhost.fetus.jp/',
                'secret' => 'SECRET KEY',
                'remoteIp' => '127.0.0.2',
            ],
        ];
    }
}
