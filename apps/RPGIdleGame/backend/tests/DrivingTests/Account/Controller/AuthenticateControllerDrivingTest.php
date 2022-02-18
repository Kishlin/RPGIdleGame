<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\Account\Controller;

use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\SecuredEndpointDrivingTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\Account\AuthenticateDrivingTestCaseTrait;
use Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character\ViewAllCharactersDrivingTestCaseTrait;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \Kishlin\Apps\RPGIdleGame\Backend\Account\Controller\AuthenticateController
 */
final class AuthenticateControllerDrivingTest extends SecuredEndpointDrivingTestCase
{
    use AuthenticateDrivingTestCaseTrait;
    use ViewAllCharactersDrivingTestCaseTrait;

    public function testItCanAuthenticate(): void
    {
        $username = 'User';
        $password = 'password';
        $token    = self::AUTHORIZATION;

        $client = self::createClient();

        $this->getContainer()->set(
            CommandBus::class,
            self::configuredCommandBusServiceMock($username, $password, $token),
        );

        $this->getContainer()->set(
            QueryBus::class,
            self::configuredQueryBusServiceMock(self::CLIENT_ID),
        );

        $headers = [
            'HTTP_CONTENT_TYPE'  => 'application/json',
            'HTTP_AUTHORIZATION' => 'Basic ' . base64_encode("{$username}:{$password}"),
        ];

        $client->request(method: 'POST', uri: '/account/authenticate', server: $headers);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $cookieNames = array_map(
            static fn (Cookie $cookie) => $cookie->getName(),
            $client->getResponse()->headers->getCookies(),
        );

        self::assertEqualsCanonicalizing(['token', 'refreshToken'], $cookieNames);
    }
}
