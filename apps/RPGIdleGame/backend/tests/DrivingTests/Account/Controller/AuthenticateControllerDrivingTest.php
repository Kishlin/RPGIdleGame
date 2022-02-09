<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\Account\Controller;

use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Backend\Apps\DrivingTests\Account\AuthenticateDrivingTestCaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \Kishlin\Apps\RPGIdleGame\Backend\Account\Controller\AuthenticateController
 */
final class AuthenticateControllerDrivingTest extends WebTestCase
{
    use AuthenticateDrivingTestCaseTrait;

    public function testItCanAuthenticate(): void
    {
        $username = 'User';
        $password = 'password';

        $client = self::createClient();

        $this->getContainer()->set(
            CommandBus::class,
            self::configuredCommandBusServiceMock($username, $password),
        );

        $headers = [
            'HTTP_CONTENT_TYPE'  => 'application/json',
            'HTTP_AUTHORIZATION' => 'Basic ' . base64_encode("{$username}:{$password}"),
        ];

        $client->request(method: 'POST', uri: '/account/authenticate', server: $headers);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = json_decode($client->getResponse()->getContent() ?: '', true);

        self::assertIsArray($data);

        foreach (['token', 'refreshToken'] as $expectedKey) {
            self::assertArrayHasKey($expectedKey, $data);
            self::assertIsString($data[$expectedKey]);
        }
    }
}
