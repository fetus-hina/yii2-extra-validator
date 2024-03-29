<?php

/**
 * @author AIZAWA Hina <hina@fetus.jp>
 * @copyright 2015-2019 by AIZAWA Hina <hina@fetus.jp>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.1.0
 */

declare(strict_types=1);

namespace jp3cki\yii2\validators;

use Yii;

/**
 * Validates that the input is ZENGIN moji sequence.
 *
 * This validator does not support full-width-katakana.
 * Please refer ZenginNameFilterValidator.
 */
class ZenginNameValidator extends internal\CharacterSequenceValidator
{
    /** @internal This property does not work */
    public bool $acceptSpace = true;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('jp3ckivalidator', '{attribute} must be ZENGIN chars.');
        }
    }

    protected function makeRegex(): string
    {
        return '/^[' .
                '0-9A-Z' .
                'ｱｲｳｴｵｶｷｸｹｺｻｼｽｾｿﾀﾁﾂﾃﾄﾅﾆﾇﾈﾉ' .
                'ﾊﾋﾌﾍﾎﾏﾐﾑﾒﾓﾔﾕﾖﾗﾘﾙﾚﾛﾜｦﾝﾞﾟ' .
                ',.｢｣()\-\/\x5c\x20' .
            ']+$/u';
    }
}
