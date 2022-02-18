<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\FunctionalTests\Account\Controller;

use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\RPGIdleGameWebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \Kishlin\Apps\RPGIdleGame\Backend\Account\Controller\LogOutController
 */
final class LogOutControllerTest extends RPGIdleGameWebTestCase
{
    public function testItFlagsCookiesAsExpired(): void
    {
        $client = self::createClient();

        $client->request('POST', '/account/logout');

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $cookies = $client->getResponse()->headers->getCookies();

        self::assertCount(2, $cookies);

        $now = strtotime('now');

        foreach ($cookies as $cookie) {
            self::assertLessThan($now, (int) $cookie->getExpiresTime());
        }
    }
}
