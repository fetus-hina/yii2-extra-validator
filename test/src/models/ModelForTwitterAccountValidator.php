<?php

declare(strict_types=1);

namespace jp3cki\yii2\validators\testsrc\models;

use jp3cki\yii2\validators\TwitterAccountValidator;
use yii\base\Model;

class ModelForTwitterAccountValidator extends Model
{
    public $value;

    public function rules()
    {
        return [
            [['value'], TwitterAccountValidator::className(), 'skipOnEmpty' => false],
        ];
    }
}
