<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.2.0
 */

namespace jp3cki\yii2\validators\internal\jpphone;

/**
 * FreeDial(0120-abc-def OR 0120-ab-cdef) like
 */
abstract class FreeDialLike extends Base
{
    protected function isValidFormat($number)
    {
        $firstPart = $this->getFirstPart();
        return !!preg_match('/^' . $firstPart .'(?:(?:-(?:\d{3}-\d{3}|\d{2}-\d{4}))|\d{6})$/', $number);
    }

    protected function isAssignedNumber($number)
    {
        $number = preg_replace('/[^0-9]+/', '', $number);
        $firstPart = $this->getFirstPart();
        $prefixList = $this->loadDataFile('others/' . $firstPart . '.json.gz');
        // 0120ABCDEFのABC部分が割り当て済みかどうかを確認する
        return !!in_array(substr($number, strlen($firstPart), 3), $prefixList, true);
    }

    abstract protected function getFirstPart();
}
