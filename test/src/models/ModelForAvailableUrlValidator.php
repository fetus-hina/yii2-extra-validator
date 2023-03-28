<?php

declare(strict_types=1);

namespace jp3cki\yii2\validators\testsrc\models;

use jp3cki\yii2\validators\AvailableUrlValidator;
use yii\base\Model;

class ModelForAvailableUrlValidator extends Model
{
    public $value;

    public function rules()
    {
        return [
            [['value'], AvailableUrlValidator::className()],
        ];
    }
}
