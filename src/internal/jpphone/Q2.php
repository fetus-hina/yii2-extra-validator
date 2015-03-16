<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.2.0
 */

namespace jp3cki\yii2\validators\internal\jpphone;

/**
 * DialQ2(0990-abc-def OR 0990-ab-cdef)
 */
class Q2 extends FreeDialLike
{
    protected function getFirstPart()
    {
        return '0990';
    }
}
