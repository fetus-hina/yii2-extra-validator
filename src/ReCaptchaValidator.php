<?php

/**
 * @author AIZAWA Hina <hina@fetus.jp>
 * @copyright 2015-2019 by AIZAWA Hina <hina@fetus.jp>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 */

namespace jp3cki\yii2\validators;

use Curl\Curl;
use Exception;
use Yii;
use stdClass;
use yii\validators\Validator;

/**
 * Validates ReCAPTCHA 2.0 input
 */
class ReCaptchaValidator extends Validator
{
    public $endPoint = 'https://www.google.com/recaptcha/api/siteverify';
    public $secret;
    public $remoteIp;
    public $userAgent;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('jp3ckivalidator', 'Please comfirm the reCAPTCHA.');
        }
        if ($this->userAgent === null) {
            $this->userAgent = internal\UserAgent::make(get_class());
        }
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $params = [
            'secret' => (string)$this->secret,
            'response' => (string)$model->$attribute,
        ];
        if ($this->remoteIp) {
            $params['remoteip'] = (string)$this->remoteIp;
        }
        if (!$this->verify($params)) {
            $this->addError($model, $attribute, $this->message);
        }
    }

    private function verify(array $params)
    {
        try {
            $curl = new Curl();
            $curl->setUserAgent($this->userAgent);
            $ret = $curl->post($this->endPoint, $params);
            if ($curl->error) {
                return false;
            }
            if (($ret instanceof stdClass) && isset($ret->success) && $ret->success === true) {
                return true;
            }
        } catch (Exception $e) {
            // do nothing
        }
        return false;
    }
}
