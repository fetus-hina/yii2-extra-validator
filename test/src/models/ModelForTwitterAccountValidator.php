<?php

declare(strict_types=1);

namespace jp3cki\yii2\validators\testsrc\models;

use Override;
use jp3cki\yii2\validators\TwitterAccountValidator;
use yii\base\Model;

class ModelForTwitterAccountValidator extends Model
{
    public $value;

    #[Override]
    public function rules()
    {
        return [
            [['value'], TwitterAccountValidator::class, 'skipOnEmpty' => false],
        ];
    }
}
