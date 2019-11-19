<?php

namespace jp3cki\yii2\validators\testsrc\models;

use yii\base\Model;
use jp3cki\yii2\validators\IdnToPunycodeFilterValidator;

class ModelForIdnToPunycodeFilterValidator extends Model
{
    public $value;

    public function rules()
    {
        return [
            [['value'], IdnToPunycodeFilterValidator::className()],
        ];
    }
}
