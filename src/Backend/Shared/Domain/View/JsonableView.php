<?php

/** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\View;

use JsonException;
use Stringable;

abstract class JsonableView implements Stringable
{
    /**
     * @throws JsonException
     */
    public function __toString(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }

    /**
     * @return array<string, mixed>
     */
    abstract public function toArray(): array;

    /**
     * @throws JsonException
     */
    public function toJson(): string
    {
        return $this->__toString();
    }
}
