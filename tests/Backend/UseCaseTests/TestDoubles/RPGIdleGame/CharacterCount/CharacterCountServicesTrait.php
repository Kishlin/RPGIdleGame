<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\RPGIdleGame\CharacterCount;

trait CharacterCountServicesTrait
{
    private ?CharacterCountGatewaySpy $characterCountGatewaySpy = null;

    public function characterCountGatewaySpy(): CharacterCountGatewaySpy
    {
        if (null === $this->characterCountGatewaySpy) {
            $this->characterCountGatewaySpy = new CharacterCountGatewaySpy();
        }

        return $this->characterCountGatewaySpy;
    }
}
