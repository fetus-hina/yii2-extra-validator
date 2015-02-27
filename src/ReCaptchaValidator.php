<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
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

    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('yii', 'Please comfirm the reCAPTCHA.');
            $this->message = 'reCAPTCHAの認証を行ってください';
        }
        if ($this->userAgent === null) {
            $this->userAgent = $this->createDefaultUserAgentString();
        }
    }

    public function validateAttribute($model, $attribute)
    {
        $params = [
            'secret' => (string)$this->secret,
            'response' => (string)$model->$attribute,
        ];
        if ($this->remoteIp) {
            $params['remoteip'] = (string)$this->remoteIp;
        }
        $url = $this->endPoint;
        try {
            $curl = new Curl();
            $curl->setUserAgent($this->userAgent);
            $ret = $curl->post($url, $params);
            if (($ret instanceof stdClass) && isset($ret->success) && $ret->success === true) {
                return;
            }
        } catch (Exception $e) {
            // do nothing
        }
        $this->addError($model, $attribute, $this->message);
    }

    private function createDefaultUserAgentString()
    {
        $parts = [];
        $parts[] = 'ReCaptchaValidator(+http://bit.ly/18571qW)';
        $parts[] = 'PHP-Curl-Class/' . Curl::VERSION;
        $parts[] = 'PHP/' . PHP_VERSION;
        $parts[] = 'curl/' . curl_version();
        return implode(' ', $parts);
    }
}
