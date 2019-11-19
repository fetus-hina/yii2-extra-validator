<?php

/**
 * @author AIZAWA Hina <hina@fetus.jp>
 * @copyright 2015-2019 by AIZAWA Hina <hina@fetus.jp>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.0.1
 */

namespace jp3cki\yii2\validators;

use yii\validators\FilterValidator;

use function idn_to_ascii;

use const INTL_IDNA_VARIANT_UTS46;

/**
 * The filter validator which converts IDN to Punycoded domain name
 */
class IdnToPunycodeFilterValidator extends FilterValidator
{
    /** @inheritdoc */
    public function init()
    {
        $this->filter = function (string $value): string {
            if (strpos($value, '/') === false) {
                return strtolower(static::idnToAscii($value));
            }

            if (strpos($value, '//') !== false) {
                return preg_replace_callback(
                    '!(?<=//)([^/:]+)!',
                    function (array $match): string {
                        return strtolower(static::idnToAscii($match[1]));
                    },
                    $value,
                    1
                );
            }

            return preg_replace_callback(
                '!^([^/:]+)!',
                function (array $match): string {
                    return strtolower(static::idnToAscii($match[1]));
                },
                $value,
                1
            );
        };
        parent::init();
    }

    protected static function idnToAscii(string $value): string
    {
        return idn_to_ascii($value, 0, INTL_IDNA_VARIANT_UTS46);
    }
}
