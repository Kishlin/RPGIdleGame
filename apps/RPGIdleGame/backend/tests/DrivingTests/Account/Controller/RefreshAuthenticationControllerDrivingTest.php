<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\Account\Controller;

use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\RPGIdleGameWebTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\Account\RefreshAuthenticationDrivingTestCaseTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \Kishlin\Apps\RPGIdleGame\Backend\Account\Controller\RefreshAuthenticationController
 */
final class RefreshAuthenticationControllerDrivingTest extends RPGIdleGameWebTestCase
{
    use RefreshAuthenticationDrivingTestCaseTrait;

    public function testItCanRefreshAuthentication(): void
    {
        $refreshToken = 'refresh-token';

        $client = self::createClient();

        $this->getContainer()->set(
            CommandBus::class,
            self::configuredCommandBusServiceMock($refreshToken),
        );

        $headers = [
            'HTTP_CONTENT_TYPE'  => 'application/json',
            'HTTP_AUTHORIZATION' => "Bearer {$refreshToken}",
        ];

        $client->request(method: 'POST', uri: '/account/refresh-authentication', server: $headers);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = json_decode($client->getResponse()->getContent() ?: '', true);

        self::assertIsArray($data);

        self::assertArrayHasKey('token', $data);
        self::assertIsString($data['token']);
    }
}
