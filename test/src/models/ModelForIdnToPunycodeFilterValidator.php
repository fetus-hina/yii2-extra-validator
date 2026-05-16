<?php

declare(strict_types=1);

namespace jp3cki\yii2\validators\testsrc\models;

use Override;
use jp3cki\yii2\validators\IdnToPunycodeFilterValidator;
use yii\base\Model;

class ModelForIdnToPunycodeFilterValidator extends Model
{
    public $value;

    #[Override]
    public function rules()
    {
        return [
            [['value'], IdnToPunycodeFilterValidator::class],
        ];
    }
}
