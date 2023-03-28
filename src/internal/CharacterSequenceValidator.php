<?php

/**
 * @author AIZAWA Hina <hina@fetus.jp>
 * @copyright 2015-2019 by AIZAWA Hina <hina@fetus.jp>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.0.2
 */

declare(strict_types=1);

namespace jp3cki\yii2\validators\internal;

use Yii;
use yii\validators\Validator;

use function is_string;
use function mb_convert_encoding;
use function preg_match;

/**
 * Validates that the input character sequence.
 */
abstract class CharacterSequenceValidator extends Validator
{
    /** @var bool Set true if you need accept space. */
    public bool $acceptSpace = false;

    /** @var string|null Charset of value. Used Yii::$app->charset if this is null. */
    public ?string $charset = null;

    /**
     * @inheritdoc
     * @return void
     */
    public function init()
    {
        parent::init();
        if ($this->charset === null) {
            $this->charset = Yii::$app->charset;
        }
    }

    /**
     * @inheritdoc
     * @return void
     */
    public function validateAttribute($model, $attribute)
    {
        if (!$this->isValid($model->$attribute)) {
            $this->addError($model, $attribute, (string)$this->message);
        }
    }

    /**
     * @inheritdoc
     * @return array{string, array}|null
     */
    protected function validateValue($value)
    {
        if (!$this->isValid($value)) {
            return [(string)$this->message, []];
        }

        return null;
    }

    protected function isValid(mixed $value): bool
    {
        if (!is_string($value)) {
            return false;
        }

        return (bool)preg_match(
            $this->makeRegex(),
            mb_convert_encoding($value, 'UTF-8', $this->charset),
        );
    }

    abstract protected function makeRegex(): string;
}
