<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\RPGIdleGame\Character\Controller;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\SecuredEndpointDrivingTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character\ViewAllCharactersDrivingTestCaseTrait;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Character\Controller\ViewAllCharactersController
 */
final class ViewAllCharactersControllerDrivingTest extends SecuredEndpointDrivingTestCase
{
    use ViewAllCharactersDrivingTestCaseTrait;

    public function testItCorrectlyUsesTheApplication(): void
    {
        $owner = self::CLIENT_ID;

        $client = self::createClient();

        $client->getCookieJar()->set(new Cookie('token', self::AUTHORIZATION));

        $this->getContainer()->set(
            QueryBus::class,
            self::configuredQueryBusServiceMock($owner),
        );

        $client->request(method: Request::METHOD_GET, uri: '/character/all');

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = json_decode($client->getResponse()->getContent() ?: '', true);

        self::assertIsArray($data);
    }
}
