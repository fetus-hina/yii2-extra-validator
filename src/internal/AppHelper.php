<?php

/**
 * @copyright 2015-2026 by AIZAWA Hina <hina@fetus.jp>
 * @license https://github.com/fetus-hina/yii2-extra-validator/blob/master/LICENSE MIT
 */

declare(strict_types=1);

namespace jp3cki\yii2\validators\internal;

use TypeError;
use Yii;
use yii\base\Application;
use yii\console\Application as CliApp;
use yii\web\Application as WebApp;
use yii\web\IdentityInterface;

use function assert;
use function is_object;

final class AppHelper
{
    /**
     * @return CliApp|WebApp<IdentityInterface>
     */
    public static function app(): Application
    {
        $app = self::instanceOf(Yii::$app, Application::class);
        assert($app instanceof WebApp || $app instanceof CliApp);
        return $app;
    }

    /**
     * @template T of object
     * @param class-string<T> $class
     * @return T
     */
    private static function instanceOf(mixed $v, string $class): object
    {
        if (!is_object($v) || !($v instanceof $class)) {
            throw new TypeError();
        }

        return $v;
    }
}
