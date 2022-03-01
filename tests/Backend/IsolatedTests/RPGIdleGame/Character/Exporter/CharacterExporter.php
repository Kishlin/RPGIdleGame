<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\RPGIdleGame\Character\Exporter;

use Kishlin\Backend\RPGIdleGame\Character\Domain\Character;
use SebastianBergmann\Exporter\Exporter;
use SebastianBergmann\RecursionContext\Context;

final class CharacterExporter extends Exporter
{
    /**
     * @param Character    $value
     * @param int          $indentation
     * @param null|Context $processed
     */
    protected function recursiveExport(&$value, $indentation, $processed = null): string
    {
        if ($value instanceof Character) {
            return "Character of id {$value->id()->value()}";
        }

        return parent::recursiveExport($value, $indentation, $processed);
    }
}
