<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\RPGIdleGame\Fight;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\SecuredEndpointDrivingTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Fight\ViewFightsForFighterDrivingTestCaseTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \Kishlin\Apps\RPGIdleGame\Backend\RPGIdleGame\Fight\Controller\ViewFightsForFighterController
 */
final class ViewFightsForFighterControllerDrivingTest extends SecuredEndpointDrivingTestCase
{
    use ViewFightsForFighterDrivingTestCaseTrait;

    public function testItCorrectlyUsesTheApplication(): void
    {
        $requester = self::CLIENT_ID;
        $fighter   = '271e2617-272f-422e-8a31-4a4422d2e13a';

        $client = self::createClient();

        $this->getContainer()->set(
            QueryBus::class,
            self::configuredQueryBusServiceMock($requester, $fighter),
        );

        $headers = [
            'HTTP_AUTHORIZATION' => self::AUTHORIZATION,
        ];

        $client->request(method: Request::METHOD_GET, uri: "/fight/all/{$fighter}", server: $headers);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = json_decode($client->getResponse()->getContent() ?: '', true);

        self::assertIsArray($data);
        self::assertCount(2, $data);
    }
}
