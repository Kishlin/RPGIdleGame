<?php

declare(strict_types=1);

namespace Kishlin\Tests\Apps\RPGIdleGame\Backend\DrivingTests\RPGIdleGame\Character\Controller;

use Kishlin\Backend\Shared\Domain\Bus\Command\CommandBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Tests\Apps\RPGIdleGame\Backend\Tools\SecuredEndpointDrivingTestCase;
use Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character\DistributeSkillPointsDrivingTestCaseTrait;
use Kishlin\Tests\Backend\Apps\DrivingTests\RPGIdleGame\Character\ViewCharacterDrivingTestCaseTrait;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
final class DistributeSkillPointsControllerDrivingTest extends SecuredEndpointDrivingTestCase
{
    use DistributeSkillPointsDrivingTestCaseTrait;
    use ViewCharacterDrivingTestCaseTrait;

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

        $client->getCookieJar()->set(new Cookie('token', self::AUTHORIZATION));

        $this->getContainer()->set(
            CommandBus::class,
            self::configuredCommandBusServiceMock($owner, $character, $health, $attack, $defense, $magik),
        );

        $this->getContainer()->set(
            QueryBus::class,
            self::configuredQueryBusServiceMock($owner, $character),
        );

        $headers = [
            'HTTP_CONTENT_TYPE' => 'application/json',
        ];

        $client->request(method: 'PUT', uri: "/character/{$character}", server: $headers, content: $content);

        self::assertResponseIsSuccessful();
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
