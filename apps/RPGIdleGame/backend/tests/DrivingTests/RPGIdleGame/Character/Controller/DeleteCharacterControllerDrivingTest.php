<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\RPGIdleGame\Character\Controller;

use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\SecuredEndpointDrivingTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character\DeleteCharacterDrivingTestCaseTrait;
use Symfony\Component\BrowserKit\Cookie;
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

        $client->getCookieJar()->set(new Cookie('token', self::AUTHORIZATION));

        $this->getContainer()->set(
            CommandBus::class,
            self::configuredCommandBusServiceMock($owner, characterId: $character),
        );

        $client->request(method: 'DELETE', uri: "/character/{$character}");

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
