<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.2.0
 */

namespace jp3cki\yii2\validators\internal\jpphone;

use yii\base\Object;

abstract class Base extends Object
{
    /**
     * Hyphen accept/require mode
     *
     * null:  accept hypen, but not required.
     * true:  require hypen
     * false: not accept hypen
     */
    public $hyphen = null;

    /**
     * Validate phone number
     *
     * @params string $number Phone number
     * @return bool
     */
    public function validate($number)
    {
        return $this->isValidFormat($number) &&
            $this->isValidHyphenStatus($number) &&
            $this->isAssignedNumber($number);
    }

    abstract protected function isValidFormat($number);

    abstract protected function isAssignedNumber($number);

    protected function isValidHyphenStatus($number)
    {
        if ($this->hyphen === false && strpos($number, '-') !== false) {
            return false;
        }
        if ($this->hyphen === true && strpos($number, '-') === false) {
            return false;
        }
        return true;
    }

    protected function loadDataFile($path)
    {
        $realpath = __DIR__ . '/../../../data/phone/jp/' . $path;
        if (!file_exists($realpath)) {
            return [];
        }
        $ret = @json_decode(file_get_contents('compress.zlib://' . $realpath));
        return is_array($ret) ? $ret : [];
    }
}
