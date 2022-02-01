<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Infrastructure;

use Exception;
use Kishlin\Backend\RPGIdleGame\Fight\Domain\Dice;

final class RandomDice implements Dice
{
    /**
     * @throws Exception
     */
    public function roll(int $maxValue): int
    {
        return $maxValue > 0 ? random_int(1, $maxValue) : 0;
    }
}
