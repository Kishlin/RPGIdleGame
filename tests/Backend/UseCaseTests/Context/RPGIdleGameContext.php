<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

abstract class RPGIdleGameContext
{
    private static ?TestServiceContainer $container = null;

    /**
     * @AfterScenario
     */
    public function clearDatabase(): void
    {
        $container = self::container();

        ReflectionHelper::writePropertyValue($container->accountGatewaySpy(), 'accounts', []);
        ReflectionHelper::writePropertyValue($container->characterGatewaySpy(), 'characters', []);
        ReflectionHelper::writePropertyValue($container->characterCountGatewaySpy(), 'characterCounts', []);
    }

    protected static function container(): TestServiceContainer
    {
        if (null === self::$container) {
            self::$container = new TestServiceContainer();
        }

        return self::$container;
    }
}
