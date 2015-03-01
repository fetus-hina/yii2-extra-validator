<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.0.1
 */

namespace jp3cki\yii2\validators\internal;

use Curl\Curl;

/**
 * Helper class for making default user-agent string
 */
class UserAgent
{
    /**
     * Make user-agent string
     *
     * @param $className string FQCN of Caller
     * @return string
     */
    public static function make($className)
    {
        $className = preg_replace('/^.+\\\\(?=[^\\\\])/', '', $className);
        $curlVersion = curl_version();
        $parts = [];
        $parts[] = $className . '(+http://bit.ly/18571qW)';
        $parts[] = 'PHP-Curl-Class/' . Curl::VERSION;
        $parts[] = 'PHP/' . PHP_VERSION;
        $parts[] = 'curl/' . $curlVersion['version'];
        return implode(' ', $parts);
    }
}
