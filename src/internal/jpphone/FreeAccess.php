<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.2.0
 */

namespace jp3cki\yii2\validators\internal\jpphone;

/**
 * FreeAccess(0800-abc-defg)
 */
class FreeAccess extends Base
{
    protected function isValidFormat($number)
    {
        return !!preg_match('/^0800(?:(?:-\d{3}-\d{4})|(?:\d{7}))$/', $number);
    }

    protected function isAssignedNumber($number)
    {
        $number = preg_replace('/[^0-9]+/', '', $number);
        $prefixList = $this->loadDataFile('others/0800.json.gz');
        // 0800ABCDEFGのABC部分が割り当て済みかどうかを確認する
        return !!in_array(substr($number, 4, 3), $prefixList, true);
    }
}
