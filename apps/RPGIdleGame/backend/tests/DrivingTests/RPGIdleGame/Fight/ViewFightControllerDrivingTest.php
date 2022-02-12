<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\RPGIdleGame\Fight;

use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\SecuredEndpointDrivingTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Fight\ViewFightDrivingTestCaseTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
final class ViewFightControllerDrivingTest extends SecuredEndpointDrivingTestCase
{
    use ViewFightDrivingTestCaseTrait;

    public function testItCorrectlyUsesTheApplication(): void
    {
        $requester = self::CLIENT_ID;
        $fight     = 'fa490962-a463-4347-8e0e-c7bcc572f65c';

        $client = self::createClient();

        $this->getContainer()->set(
            QueryBus::class,
            self::configuredQueryBusServiceMock($requester, $fight),
        );

        $headers = [
            'HTTP_AUTHORIZATION' => self::AUTHORIZATION,
        ];

        $client->request(method: Request::METHOD_GET, uri: "/fight/{$fight}", server: $headers);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $data = json_decode($client->getResponse()->getContent() ?: '', true);

        self::assertIsArray($data);
        self::assertArrayHasKey('id', $data);
        self::assertSame($fight, $data['id']);
    }
}
