<?php

/**
 * @author AIZAWA Hina <hina@fetus.jp>
 * @copyright 2015-2019 by AIZAWA Hina <hina@fetus.jp>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace jp3cki\yii2\validators;

use Throwable;
use Yii;
use jp3cki\yii2\validators\internal\UserAgent;
use yii\httpclient\Client as HttpClient;
use yii\validators\Validator;

use function is_array;

/**
 * Validates ReCAPTCHA 2.0 input
 */
class ReCaptchaValidator extends Validator
{
    public string $endPoint = 'https://www.google.com/recaptcha/api/siteverify';
    public ?string $secret = null;
    public ?string $remoteIp = null;
    public ?string $userAgent = null;

    /**
     * @inheritdoc
     * @return void
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('jp3ckivalidator', 'Please comfirm the reCAPTCHA.');
        }

        if ($this->userAgent === null) {
            $this->userAgent = UserAgent::make(static::class);
        }
    }

    /**
     * @inheritdoc
     * @return void
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
            $this->addError($model, $attribute, (string)$this->message);
        }
    }

    /**
     * @param array<string, string> $params
     */
    private function verify(array $params): bool
    {
        try {
            $client = Yii::createObject([
                'class' => HttpClient::class,
                'responseConfig' => [
                    'format' => HttpClient::FORMAT_JSON,
                ],
            ]);
            $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($this->endPoint)
                ->addHeaders([
                    'User-Agent' => (string)$this->userAgent,
                ])
                ->setData($params)
                ->send();

            if (!$response->isOk) {
                return false;
            }

            $data = $response->data;
            return is_array($data) && ($data['success'] ?? false) === true;
        } catch (Throwable $e) {
            // do nothing
        }

        return false;
    }
}
