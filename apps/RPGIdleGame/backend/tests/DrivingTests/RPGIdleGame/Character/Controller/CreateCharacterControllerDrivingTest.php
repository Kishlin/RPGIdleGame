<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\RPGIdleGame\Character\Controller;

use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\SecuredEndpointDrivingTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character\CreateCharacterDrivingTestCaseTrait;
use Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character\ViewCharacterDrivingTestCaseTrait;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Character\Controller\CreateCharacterController
 */
final class CreateCharacterControllerDrivingTest extends SecuredEndpointDrivingTestCase
{
    use CreateCharacterDrivingTestCaseTrait;
    use ViewCharacterDrivingTestCaseTrait;

    public function testItCanCreateACharacter(): void
    {
        $characterId = '60ce509c-2b33-48f5-ade4-44806085465b';
        $owner       = self::CLIENT_ID;
        $name        = 'Kishlin';

        $requestData = json_encode(['characterName' => $name]);
        assert(is_string($requestData));

        $client = self::createClient();

        $client->getCookieJar()->set(new Cookie('token', self::AUTHORIZATION));

        $this->getContainer()->set(
            CommandBus::class,
            self::configuredCommandBusServiceMock($owner, $name, $characterId),
        );

        $this->getContainer()->set(
            QueryBus::class,
            self::configuredQueryBusServiceMock($owner, $characterId)
        );

        $headers = [
            'HTTP_CONTENT_TYPE' => 'application/json',
        ];

        $client->request(method: 'POST', uri: '/character/create', server: $headers, content: $requestData);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = json_decode($client->getResponse()->getContent() ?: '', true);
        assert(is_array($data));

        self::assertArrayHasKey('id', $data);
        self::assertSame($characterId, $data['id']);
    }
}
