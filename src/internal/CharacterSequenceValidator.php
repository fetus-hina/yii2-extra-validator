<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.0.2
 */

namespace jp3cki\yii2\validators\internal;

use Yii;
use yii\validators\Validator;

/**
 * Validates that the input character sequence.
 */
abstract class CharacterSequenceValidator extends Validator
{
    /** @var bool Set true if you need accept space. */
    public $acceptSpace = false;

    /** @var string|null Charset of value. Used Yii::$app->charset if this is null. */
    public $charset;

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        if ($this->charset === null) {
            $this->charset = Yii::$app->charset;
        }
    }

    /** @inheritdoc */
    public function validateAttribute($model, $attribute)
    {
        if (!$this->isValid($model->$attribute)) {
            $this->addError($model, $attribute, $this->message);
        }
    }

    /** @inheritdoc */
    protected function validateValue($value)
    {
        if (!$this->isValid($value)) {
            return [$this->message, []];
        }
        return null;
    }

    protected function isValid($value)
    {
        return preg_match(
            $this->makeRegex(),
            mb_convert_encoding($value, 'UTF-8', $this->charset)
        );
    }

    abstract protected function makeRegex();
}
