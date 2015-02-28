<?php
namespace jp3cki\yii2\validators\testsrc;

use PHPUnit_Framework_TestCase;
use Yii;
use yii\base\NotSupprtException;
use yii\helpers\ArrayHelper;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        $vendorDir = __DIR__ . '/../../vendor';
        $vendorAutoload = $vendorDir . '/autoload.php';
        if (file_exists($vendorAutoload)) {
            require_once($vendorAutoload);
        } else {
            throw new NotSupportedException("Vendor autoload file '{$vendorAutoload}' is missing.");
        }
        require_once($vendorDir . '/yiisoft/yii2/Yii.php');
        Yii::setAlias('@vendor', $vendorDir);
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->destroyApplication();
    }

    protected function mockApplication($language = 'en-US', $config = [], $appClass = '\yii\console\Application')
    {
        new $appClass(ArrayHelper::merge([
                'id' => 'testapp',
                'basePath' => __DIR__ . '/..',
                'vendorPath' => __DIR__ . '/../../vendor',
                'language' => $language,
                'bootstrap' => [
                    'jp3cki\yii2\validators\internal\Bootstrap',
                ],
            ], $config)
        );
    }

    protected function destroyApplication()
    {
        \Yii::$app = null;
    }
}
