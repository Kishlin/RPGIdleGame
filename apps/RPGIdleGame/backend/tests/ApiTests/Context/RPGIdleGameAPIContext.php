<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Context;

use Behat\Behat\Context\Context;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Database\DatabaseInterface;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\Database\PostgresDatabase;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\HTTPClient\HTTPClientInterface;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\HTTPClient\HTTPClientUsingCurl;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\ApiTests\HTTPClient\Response;

abstract class RPGIdleGameAPIContext implements Context
{
    protected const AUTHENTICATION_FOR_CLIENT   = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjNmRjM2ZkOTNmNTYiLCJhdWQiOiJjNmRjM2ZkOTNmNTYiLCJpYXQiOjE2NDQ0MTczNDYsInVzZXIiOiI3ZDM4Nzc0MC01YzE1LTQ3MTItYmRjZi01MTI2YzI4ZmMxMGEifQ.zeWXeTfhDa_VNoQUtNJ-IJHJzhvesAbEpiJGMPEh-fo';
    protected const AUTHENTICATION_FOR_STRANGER = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJjNmRjM2ZkOTNmNTYiLCJhdWQiOiJjNmRjM2ZkOTNmNTYiLCJpYXQiOjE2NDQ0MTczNDYsInVzZXIiOiJzdHJhbmdlciJ9.Mm5O1PjHXURatw0LGt8gPwmw52YCwKBeKc3-snCCZYo';

    protected const CLIENT_UUID = '7d387740-5c15-4712-bdcf-5126c28fc10a';

    private const SYMFONY_SERVER = 'http://localhost:8000';

    protected ?Response $response = null;

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
