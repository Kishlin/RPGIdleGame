<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\RPGIdleGame\Character\Controller;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\SecuredEndpointDrivingTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character\ViewCharacterDrivingTestCaseTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Character\Controller\ViewCharacterController
 */
final class ViewCharacterControllerDrivingTest extends SecuredEndpointDrivingTestCase
{
    use ViewCharacterDrivingTestCaseTrait;

    public function testItCorrectlyUsesTheApplication(): void
    {
        $owner     = self::CLIENT_ID;
        $character = 'e738c606-90f5-42f5-ba84-3a62b1d86958';

        $client = self::createClient();

        $this->getContainer()->set(
            QueryBus::class,
            self::configuredQueryBusServiceMock($owner, characterId: $character),
        );

        $headers = [
            'HTTP_AUTHORIZATION' => self::AUTHORIZATION,
        ];

        $client->request(method: Request::METHOD_GET, uri: "/character/{$character}", server: $headers);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = json_decode($client->getResponse()->getContent() ?: '', true);

        self::assertIsArray($data);
        self::assertArrayHasKey('id', $data);
        self::assertSame($character, $data['id']);
    }
}
