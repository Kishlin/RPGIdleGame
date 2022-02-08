<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Fight\Domain\Exporter;

use Kishlin\Backend\RPGIdleGame\Fight\Domain\Fight;
use SebastianBergmann\Exporter\Exporter;
use SebastianBergmann\RecursionContext\Context;

final class FightExporter extends Exporter
{
    /**
     * @param Fight        $value
     * @param int          $indentation
     * @param null|Context $processed
     */
    protected function recursiveExport(&$value, $indentation, $processed = null): string
    {
        if ($value instanceof Fight) {
            return "Fight of id {$value->id()->value()}";
        }

        return parent::recursiveExport($value, $indentation, $processed);
    }
}
