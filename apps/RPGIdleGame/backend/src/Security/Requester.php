<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Apps\RPGIdleGame\Backend\Security;

use Stringable;

final class Requester implements Stringable
{
    private function __construct(
        private string $id,
    ) {
    }

    public function __toString(): string
    {
        return $this->id;
    }

    public function id(): string
    {
        return $this->id;
    }

    public static function fromScalar(string $id): self
    {
        return new self($id);
    }
}
