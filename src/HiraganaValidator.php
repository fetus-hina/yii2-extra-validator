<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.0.2
 */

namespace jp3cki\yii2\validators;

use Yii;

/**
 * Validates that the input is hiragana.
 */
class HiraganaValidator extends internal\CharacterSequenceValidator
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('jp3ckivalidator', '{attribute} must be hiragana.');
        }
    }

    protected function makeRegex()
    {
        // Hiragana range: U+3041(ぁ)..U+309F(ゟ:より)
        return $this->acceptSpace ? '/^[ぁ-ゟー　[:space:]]*$/u' : '/^[ぁ-ゟー]*$/u';
    }
}
