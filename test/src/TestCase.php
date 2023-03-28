<?php

declare(strict_types=1);

namespace jp3cki\yii2\validators\testsrc;

use Yii;
use jp3cki\yii2\validators\internal\BootstrapValidators;
use yii\helpers\ArrayHelper;

use function file_exists;
use function gc_collect_cycles;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    public static function setUpBeforeClass(): void
    {
        $vendorDir = __DIR__ . '/../../vendor';
        $vendorAutoload = $vendorDir . '/autoload.php';
        if (file_exists($vendorAutoload)) {
            require_once $vendorAutoload;
        } else {
            throw new NotSupportedException("Vendor autoload file '{$vendorAutoload}' is missing.");
        }
        require_once $vendorDir . '/yiisoft/yii2/Yii.php';
        Yii::setAlias('@vendor', $vendorDir);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->destroyApplication();
        gc_collect_cycles();
    }

    protected function mockApplication($language = 'en-US', $config = [], $appClass = '\yii\console\Application')
    {
        new $appClass(ArrayHelper::merge(
            [
                'id' => 'testapp',
                'basePath' => __DIR__ . '/..',
                'vendorPath' => __DIR__ . '/../../vendor',
                'language' => $language,
                'bootstrap' => [
                    BootstrapValidators::class,
                ],
            ],
            $config,
        ));
    }

    protected function destroyApplication()
    {
        Yii::$app = null;
    }
}
