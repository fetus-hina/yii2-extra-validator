<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.0.1
 */

namespace jp3cki\yii2\validators;

use Yii;
use yii\validators\Validator;
use Curl\Curl;

/**
 * Validate URL reachability
 */
class AvailableUrlValidator extends Validator
{
    /** @var string User-Agent string */
    public $userAgent;

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('jp3ckivalidator', '{attribute} is not a valid URL that is able to access.');
        }
        if ($this->userAgent  === null) {
            $this->userAgent = $this->createDefaultUserAgentString();
        }
    }

    /** @inheritdoc */
    public function validateAttribute($model, $attribute)
    {
        if (!$this->isAbleToAccess($model->$attribute)) {
            $this->addError($model, $attribute, $this->message);
        }
    }

    /** @inheritdoc */
    protected function validateValue($value)
    {
        if (!$this->isAbleToAccess($value)) {
            return [$this->message, []];
        }
        return null;
    }

    private function isAbleToAccess($url)
    {
        $curl = new Curl();
        $curl->setUserAgent($this->userAgent);
        $curl->head($url);
        return !$curl->error;
    }

    private function createDefaultUserAgentString()
    {
        $curlVersion = curl_version();
        $parts = [];
        $parts[] = 'AvailableUrlValidator(+http://bit.ly/18571qW)';
        $parts[] = 'PHP-Curl-Class/' . Curl::VERSION;
        $parts[] = 'PHP/' . PHP_VERSION;
        $parts[] = 'curl/' . $curlVersion['version'];
            return implode(' ', $parts);
    }
}