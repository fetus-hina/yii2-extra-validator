<?php

/**
 * @author AIZAWA Hina <hina@fetus.jp>
 * @copyright 2015-2019 by AIZAWA Hina <hina@fetus.jp>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.0.2
 */

declare(strict_types=1);

namespace jp3cki\yii2\validators;

use Yii;

/**
 * Validates that the input is katakana.
 *
 * This validator does not support half-width-katakana.
 */
class KatakanaValidator extends internal\CharacterSequenceValidator
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('jp3ckivalidator', '{attribute} must be katakana.');
        }
    }

    protected function makeRegex(): string
    {
        // Katakana range: U+30A0(゠:人名等に使用する区切り)..U+30FF(ヿ:コト)
        return $this->acceptSpace ? '/^[゠-ヿ　[:space:]]*$/u' : '/^[゠-ヿ]*$/u';
    }
}
