<?php

declare(strict_types=1);

namespace Kishlin\Backend\Account\Infrastructure;

use Exception;
use Kishlin\Backend\Account\Domain\SaltGenerator;

final class SaltGeneratorUsingRandomBytes implements SaltGenerator
{
    /**
     * @throws Exception
     */
    public function salt(): string
    {
        return bin2hex(random_bytes(20));
    }
}
