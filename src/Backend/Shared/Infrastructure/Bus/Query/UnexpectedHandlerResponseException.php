<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Bus\Query;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;
use RuntimeException;

final class UnexpectedHandlerResponseException extends RuntimeException
{
    public function __construct(Query $query)
    {
        $queryClass = $query::class;

        parent::__construct("Failed to get an acceptable response for query of type <{$queryClass}>.");
    }
}
