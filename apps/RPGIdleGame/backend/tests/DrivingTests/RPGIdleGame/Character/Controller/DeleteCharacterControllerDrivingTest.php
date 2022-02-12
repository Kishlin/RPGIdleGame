<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\RPGIdleGame\Character\Controller;

use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\SecuredEndpointDrivingTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character\DeleteCharacterDrivingTestCaseTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Character\Controller\DeleteCharacterController
 */
final class DeleteCharacterControllerDrivingTest extends SecuredEndpointDrivingTestCase
{
    use DeleteCharacterDrivingTestCaseTrait;

    public function testItCorrectlyUsesTheApplication(): void
    {
        $owner     = self::CLIENT_ID;
        $character = '0c6d40df-03fa-4085-9b40-cacdf4a26cdc';

        $client = self::createClient();

        $this->getContainer()->set(
            CommandBus::class,
            self::configuredCommandBusServiceMock($owner, characterId: $character),
        );

        $headers = [
            'HTTP_AUTHORIZATION' => self::AUTHORIZATION,
        ];

        $client->request(method: 'DELETE', uri: "/character/{$character}", server: $headers);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
