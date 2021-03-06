<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests;

use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Account\AccountServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Character\CharacterServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\CharacterCount\CharacterCountServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\CharacterStats\CharacterStatsServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\Fight\FightServicesTrait;
use Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging\MessagingServicesTrait;

final class TestServiceContainer
{
    use AccountServicesTrait;
    use CharacterCountServicesTrait;
    use CharacterServicesTrait;
    use CharacterStatsServicesTrait;
    use FightServicesTrait;
    use MessagingServicesTrait;

    public function serviceContainer(): self
    {
        return $this;
    }
}
