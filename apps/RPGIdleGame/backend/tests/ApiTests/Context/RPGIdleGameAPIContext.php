<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

use Behat\Behat\Context\Context;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Database\DatabaseInterface;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Database\PostgresDatabase;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\HTTPClient\HTTPClientInterface;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\HTTPClient\HTTPClientUsingCurl;

abstract class RPGIdleGameAPIContext implements Context
{
    private const SYMFONY_SERVER = 'http://localhost:8000';
    protected ?int $responseCode = null;

    private static ?DatabaseInterface $database = null;

    private static ?HTTPClientInterface $client = null;

    /**
     * @AfterScenario
     */
    public function reloadDatabase(): void
    {
        self::database()->refreshDatabase([
            'fight_turns',
            'fight_initiators',
            'fight_opponents',
            'fights',
            'characters',
            'character_counts',
            'accounts',
        ]);
    }

    protected static function client(): HTTPClientInterface
    {
        if (null === self::$client) {
            self::$client = new HTTPClientUsingCurl(self::SYMFONY_SERVER);
        }

        return self::$client;
    }

    protected static function database(): DatabaseInterface
    {
        if (null === self::$database) {
            self::$database = new PostgresDatabase();
        }

        return self::$database;
    }
}
