<?php

declare(strict_types=1);

namespace jp3cki\yii2\validators\test\internal;

use TypeError;
use Yii;
use jp3cki\yii2\validators\internal\AppHelper;
use jp3cki\yii2\validators\testsrc\TestCase;
use yii\console\Application as CliApp;
use yii\web\Application as WebApp;

class AppHelperTest extends TestCase
{
    public function testReturnsConsoleApplication(): void
    {
        $this->mockApplication();
        $app = AppHelper::app();
        $this->assertInstanceOf(CliApp::class, $app);
        $this->assertSame(Yii::$app, $app);
    }

    public function testReturnsWebApplication(): void
    {
        $this->mockApplication('en-US', [
            'components' => [
                'request' => [
                    'cookieValidationKey' => 'test',
                    'scriptFile' => __FILE__,
                    'scriptUrl' => '/index.php',
                ],
            ],
        ], WebApp::class);
        $app = AppHelper::app();
        $this->assertInstanceOf(WebApp::class, $app);
        $this->assertSame(Yii::$app, $app);
    }

    public function testThrowsWhenAppIsNull(): void
    {
        Yii::$app = null;
        $this->expectException(TypeError::class);
        AppHelper::app();
    }
}
