<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\Account\Controller;

use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\RPGIdleGameWebTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\Account\RefreshAuthenticationDrivingTestCaseTrait;
use Symfony\Component\BrowserKit\Cookie as BrowserKitCookie;
use Symfony\Component\HttpFoundation\Cookie;
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

        $client->getCookieJar()->set(new BrowserKitCookie('refreshToken', $refreshToken));

        $client->request(method: 'POST', uri: '/account/refresh-authentication');

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $cookieNames = array_map(
            static fn (Cookie $cookie) => $cookie->getName(),
            $client->getResponse()->headers->getCookies(),
        );

        self::assertSame(['token'], $cookieNames);
    }
}
