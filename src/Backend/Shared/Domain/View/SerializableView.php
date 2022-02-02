<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\View;

use Serializable;

abstract class SerializableView implements Serializable
{
    final protected function __construct()
    {
    }

    /**
     * @return array<string, mixed>
     */
    abstract public function __serialize(): array;

    /**
     * @param array<string, mixed> $data
     */
    abstract public function __unserialize(array $data): void;

    public function serialize(): string
    {
        $serialized = json_encode($this->__serialize());
        assert(false !== $serialized);

        return $serialized;
    }

    public function unserialize(string $data): static
    {
        /** @var array<string, int|string> $source */
        $source = json_decode($data, true);

        $view = new static();
        $view->__unserialize($source);

        return $view;
    }
}
