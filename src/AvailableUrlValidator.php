<?php

/**
 * @author AIZAWA Hina <hina@fetus.jp>
 * @copyright 2015-2019 by AIZAWA Hina <hina@fetus.jp>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.0.1
 */

declare(strict_types=1);

namespace jp3cki\yii2\validators;

use Yii;
use jp3cki\yii2\validators\internal\UserAgent;
use yii\httpclient\Client as HttpClient;
use yii\httpclient\Exception;
use yii\validators\Validator;

use function is_string;

/**
 * Validate URL reachability
 */
class AvailableUrlValidator extends Validator
{
    /** @var string User-Agent string */
    public $userAgent;

    /**
     * @inheritdoc
     * @return void
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('jp3ckivalidator', '{attribute} is not a valid URL that is able to access.');
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
        if (!$this->isAbleToAccess($model->$attribute)) {
            $this->addError($model, $attribute, (string)$this->message);
        }
    }

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        if (!$this->isAbleToAccess($value)) {
            return [$this->message, []];
        }

        return null;
    }

    private function isAbleToAccess(mixed $url): bool
    {
        if (!is_string($url)) {
            return false;
        }

        $http = Yii::createObject(HttpClient::class);
        try {
            $response = $http->createRequest()
                ->setMethod('HEAD')
                ->setUrl($url)
                ->send();
            return $response->isOk;
        } catch (Exception $e) {
            return false;
        }
    }
}
