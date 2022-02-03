<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Domain;

interface SaltGenerator
{
    public function salt(): string;
}
