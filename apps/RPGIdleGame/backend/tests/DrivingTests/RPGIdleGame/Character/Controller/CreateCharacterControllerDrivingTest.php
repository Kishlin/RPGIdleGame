<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\RPGIdleGame\Character\Controller;

use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\SecuredEndpointDrivingTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character\CreateCharacterDrivingTestCaseTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Character\Controller\CreateCharacterController
 */
final class CreateCharacterControllerDrivingTest extends SecuredEndpointDrivingTestCase
{
    use CreateCharacterDrivingTestCaseTrait;

    public function testItCanCreateACharacter(): void
    {
        $owner = self::CLIENT_ID;
        $name  = 'Kishlin';

        $requestData = json_encode(['characterName' => $name]);
        assert(is_string($requestData));

        $client = self::createClient();

        $this->getContainer()->set(
            CommandBus::class,
            self::configuredCommandBusServiceMock($owner, $name),
        );

        $headers = [
            'HTTP_CONTENT_TYPE'  => 'application/json',
            'HTTP_AUTHORIZATION' => self::AUTHORIZATION,
        ];

        $client->request(method: 'POST', uri: '/character/create', server: $headers, content: $requestData);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = json_decode($client->getResponse()->getContent() ?: '', true);
        assert(is_array($data));

        self::assertArrayHasKey('characterId', $data);
        self::assertIsString($data['characterId']);
    }
}
