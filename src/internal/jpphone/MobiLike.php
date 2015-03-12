<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.2.0
 */

namespace jp3cki\yii2\validators\internal\jpphone;

/**
 * Mobile phone (090-abcd-efgh) like
 */
abstract class MobiLike extends Base
{
    protected function isValidFormat($number)
    {
        $firstPart = $this->getFirstPart();
        return !!preg_match('/^(?:' . implode('|', $firstPart) . ')(?:(?:-\d{4}-\d{4})|\d{8})$/', $number);
    }

    protected function isAssignedNumber($number)
    {
        $number = preg_replace('/[^0-9]+/', '', $number);
        $firstPart = substr($number, 0, 3);
        $prefixList = $this->loadDataFile('others/' . $firstPart . '.json.gz');
        return !!in_array(substr($number, 3, 4), $prefixList, true);
    }

    abstract protected function getFirstPart();
}
