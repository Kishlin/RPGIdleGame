<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\RPGIdleGame\Fight\Controller;

use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\SecuredEndpointDrivingTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Fight\InitiateAFightDrivingTestCaseTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Fight\Controller\InitiateAFightController
 */
final class InitiateAFightControllerDrivingTest extends SecuredEndpointDrivingTestCase
{
    use InitiateAFightDrivingTestCaseTrait;

    public function testItCorrectlyUsesTheApplication(): void
    {
        $owner   = self::CLIENT_ID;
        $fighter = '0c6d40df-03fa-4085-9b40-cacdf4a26cdc';
        $fight   = '0cc9a793-a44d-4bc3-a0a6-18cb17b8dd3d';

        $client = self::createClient();

        $this->getContainer()->set(
            CommandBus::class,
            self::configuredCommandBusServiceMock($owner, $fighter, $fight),
        );

        $headers = [
            'HTTP_AUTHORIZATION' => self::AUTHORIZATION,
        ];

        $client->request(method: Request::METHOD_POST, uri: "/fight/initiate/{$fighter}", server: $headers);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = json_decode($client->getResponse()->getContent() ?: '', true);
        assert(is_array($data));

        self::assertArrayHasKey('fightId', $data);
        self::assertSame($fight, $data['fightId']);
    }
}
