<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Bus\Query;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;
use RuntimeException;

final class QueryNotRegisteredError extends RuntimeException
{
    public function __construct(Query $query)
    {
        $queryClass = $query::class;

        parent::__construct("The query <{$queryClass}> has no query handler associated");
    }
}
