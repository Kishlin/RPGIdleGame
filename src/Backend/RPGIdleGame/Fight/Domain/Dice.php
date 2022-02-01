<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Fight\Domain;

interface Dice
{
    /**
     * @return int Random int between 1 and $maxvalue
     */
    public function roll(int $maxValue): int;
}
