<?php

/**
 * @author AIZAWA Hina <hina@fetus.jp>
 * @copyright 2015-2026 by AIZAWA Hina <hina@fetus.jp>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 * @since 1.0.0
 */

declare(strict_types=1);

namespace jp3cki\yii2\validators\internal;

use Override;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;

final class BootstrapValidators implements BootstrapInterface
{
    /**
     * @param Application $app
     * @return void
     */
    #[Override]
    public function bootstrap($app)
    {
        Yii::setAlias('@jp3ckivalidatormessages', __DIR__ . '/../../messages');
        $i18n = AppHelper::app()->getI18n();
        if (!isset($i18n->translations['jp3ckivalidator'])) {
            $i18n->translations['jp3ckivalidator'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en-US',
                'basePath' => '@jp3ckivalidatormessages',
            ];
        }
    }
}
