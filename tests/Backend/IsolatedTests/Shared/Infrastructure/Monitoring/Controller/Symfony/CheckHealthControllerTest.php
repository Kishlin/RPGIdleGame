<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Shared\Infrastructure\Monitoring\Controller\Symfony;

use Kishlin\Backend\Shared\Infrastructure\Monitoring\Controller\Symfony\AbstractCheckHealthController;
use Kishlin\Backend\Shared\Infrastructure\Monitoring\Probe\Probe;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @internal
 * @covers \Kishlin\Backend\Shared\Infrastructure\Monitoring\Controller\Symfony\AbstractCheckHealthController
 */
final class CheckHealthControllerTest extends TestCase
{
    public function testCheckHealthController(): void
    {
        $probesData = [
            'probeOne'  => true,
            'probTwo'   => true,
            'probThree' => false,
        ];

        $controller = $this->controller($probesData);
        $response   = $controller(); // Controller is an invokable.

        self::assertInstanceOf(JsonResponse::class, $response);

        $content = $response->getContent();
        if (false === $content) {
            self::fail('Failed to retrieve any content from the response.');
        }

        self::assertSame($probesData, json_decode($content, true));
    }

    /**
     * @param array<string, bool> $probesData
     */
    private function controller(array $probesData): AbstractCheckHealthController
    {
        $probes = array_map([$this, 'probeMock'], array_keys($probesData), $probesData);

        return new class($probes) extends AbstractCheckHealthController {};
    }

    private function probeMock(string $name, bool $isAlive): Probe
    {
        $probe = $this->createMock(Probe::class);
        $probe->expects(self::once())->method('name')->willReturn($name);
        $probe->expects(self::once())->method('isAlive')->willReturn($isAlive);

        return $probe;
    }
}
