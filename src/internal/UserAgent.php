<?php

/**
 * @author AIZAWA Hina <hina@fetus.jp>
 * @copyright 2015-2019 by AIZAWA Hina <hina@fetus.jp>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.0.1
 */

declare(strict_types=1);

namespace jp3cki\yii2\validators\internal;

use function implode;
use function preg_replace;

use const PHP_VERSION;

/**
 * Helper class for making default user-agent string
 */
class UserAgent
{
    /**
     * Make user-agent string
     *
     * @param $className string FQCN of Caller
     */
    public static function make(string $className): string
    {
        $className = preg_replace('/^.+\\\\(?=[^\\\\])/', '', $className);
        $parts = [];
        $parts[] = $className . '(+http://bit.ly/18571qW)';
        $parts[] = 'PHP/' . PHP_VERSION;
        return implode(' ', $parts);
    }
}
