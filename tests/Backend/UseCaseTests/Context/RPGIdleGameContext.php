<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\Context;

use Behat\Behat\Context\Context;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;

final class RPGIdleGameContext implements Context
{
    use AccountTrait;

    public function __construct(
        private TestServiceContainer $container = new TestServiceContainer()
    ) {
    }
}
