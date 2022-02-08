<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Behat\Behat\Context\Context;
use Kishlin\Tests\Backend\Tools\ReflectionHelper;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;
use ReflectionException;

abstract class RPGIdleGameContext implements Context
{
    protected const EMAIL_TO_USE          = 'user@example.com';
    protected const NEW_ACCOUNT_UUID      = '51cefa3e-c223-469e-a23c-61a32e4bf048';
    protected const EXISTING_ACCOUNT_UUID = '255c03d2-4149-4fe2-b922-65ed3ce4be0e';

    protected const SECRET_KEY = 'ThisKeyIsNotSoSecretButItIsTests';
    protected const ALGORITHM  = 'HS256';

    protected const CLIENT_UUID   = '97c116cc-21b0-4624-8e02-88b9b1a977a7';
    protected const STRANGER_UUID = 'df42d3aa-10ea-4ca3-936b-2bba5ae16fe6';
    protected const FIGHTER_UUID  = 'fa2e098a-1ed4-4ddb-91d1-961e0af7143b';
    protected const OPPONENT_UUID = 'e26b33be-5253-4cc3-8480-a15e80f18b7a';
    protected const FIGHT_ID      = '695fbddb-1863-4170-85ba-c0f146b341ad';

    private static ?TestServiceContainer $container = null;

    /**
     * @AfterScenario
     *
     * @throws ReflectionException
     */
    public function clearDatabase(): void
    {
        $container = self::container();

        ReflectionHelper::writePropertyValue($container->accountGatewaySpy(), 'accounts', []);
        ReflectionHelper::writePropertyValue($container->characterGatewaySpy(), 'characters', []);
        ReflectionHelper::writePropertyValue($container->characterCountGatewaySpy(), 'characterCounts', []);
        ReflectionHelper::writePropertyValue($container->characterStatsGatewaySpy(), 'characterStats', []);
        ReflectionHelper::writePropertyValue($container->fightGatewaySpy(), 'fights', []);
    }

    protected static function container(): TestServiceContainer
    {
        if (null === self::$container) {
            self::$container = new TestServiceContainer();
        }

        return self::$container;
    }
}
