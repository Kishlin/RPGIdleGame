<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\Account\Controller;

use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Backend\Apps\DrivingTests\Account\SignupDrivingTestCaseTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \Kishlin\Apps\RPGIdleGame\Backend\Account\Controller\SignupController
 */
final class SignupControllerDrivingTest extends WebTestCase
{
    use SignupDrivingTestCaseTrait;

    public function testItCanSignUp(): void
    {
        $username = 'User';
        $password = 'password';
        $email    = 'user@example.com';

        $requestData = json_encode(['email' => $email]);
        assert(is_string($requestData));

        $client = self::createClient();

        $this->getContainer()->set(
            CommandBus::class,
            self::configuredCommandBusServiceMock($username, $email, $password),
        );

        $headers = [
            'HTTP_CONTENT_TYPE'  => 'application/json',
            'HTTP_AUTHORIZATION' => 'Basic ' . base64_encode("{$username}:{$password}"),
        ];

        $client->request(method: 'POST', uri: '/account/signup', server: $headers, content: $requestData);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        $data = json_decode($client->getResponse()->getContent() ?: '', true);
        assert(is_array($data));

        self::assertArrayHasKey('token', $data);
        self::assertArrayHasKey('refreshToken', $data);

        self::assertIsString($data['token']);
        self::assertIsString($data['refreshToken']);
    }
}
