<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain;

use Kishlin\Backend\Account\Domain\ValueObject\AccountId;

interface SaltGenerator
{
    public function salt(): string;
}
