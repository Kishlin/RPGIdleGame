<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Domain\Bus\Query;

/**
 * QueryHandlers must have an __invoke() method that takes one parameter, an instance of \Kishlin\Backend\Shared\Domain\Bus\Query\Query
 * The method must either return null, or an instance of \Kishlin\Backend\Shared\Domain\Bus\Query\Response
 * The Response (or null) will be returned to the client by the QueryBus.
 *
 * @see \Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus
 */
interface QueryHandler
{
}
