<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.2.0
 */

namespace jp3cki\yii2\validators\internal\jpphone;

/**
 * FreeDial(0120-abc-def OR 0120-ab-cdef)
 */
class FreeDial extends FreeDialLike
{
    protected function getFirstPart()
    {
        return '0120';
    }
}
