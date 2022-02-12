<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\RPGIdleGame\Character\Controller;

use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\SecuredEndpointDrivingTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character\DistributeSkillPointsDrivingTestCaseTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
final class DistributeSkillPointsControllerTest extends SecuredEndpointDrivingTestCase
{
    use DistributeSkillPointsDrivingTestCaseTrait;

    public function testItCorrectlyUsesTheApplication(): void
    {
        $owner     = self::CLIENT_ID;
        $character = '0c6d40df-03fa-4085-9b40-cacdf4a26cdc';

        $health  = 10;
        $attack  = 8;
        $defense = 6;
        $magik   = 4;

        $content = json_encode(['health' => $health, 'attack' => $attack, 'defense' => $defense, 'magik' => $magik]);
        assert(is_string($content));

        $client = self::createClient();

        $this->getContainer()->set(
            CommandBus::class,
            self::configuredCommandBusServiceMock($owner, $character, $health, $attack, $defense, $magik),
        );

        $headers = [
            'HTTP_CONTENT_TYPE'  => 'application/json',
            'HTTP_AUTHORIZATION' => self::AUTHORIZATION,
        ];

        $client->request(method: 'PUT', uri: "/character/{$character}", server: $headers, content: $content);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
