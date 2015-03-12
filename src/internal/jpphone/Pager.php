<?php
/**
 * @author AIZAWA Hina <hina@bouhime.com>
 * @copyright 2015 by AIZAWA Hina <hina@bouhime.com>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.2.0
 */

namespace jp3cki\yii2\validators\internal\jpphone;

/**
 * Pager (pocket-bell) 020-xxxx-xxxx
 */
class Pager extends MobiLike
{
    protected function getFirstPart()
    {
        return ['020'];
    }
}
