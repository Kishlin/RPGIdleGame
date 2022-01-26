<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\UseCaseTests\TestDoubles\Shared\Messaging;

use Kishlin\Backend\Shared\Domain\Bus\Query\Query;
use Kishlin\Backend\Shared\Domain\Bus\Query\QueryBus;
use Kishlin\Backend\Shared\Domain\Bus\Query\Response;
use Kishlin\Tests\Backend\UseCaseTests\TestServiceContainer;
use RuntimeException;

final class TestQueryBus implements QueryBus
{
    /** @noinspection PhpPropertyOnlyWrittenInspection */
    public function __construct(
        /** @phpstan-ignore-next-line  */
        private TestServiceContainer $testServiceContainer
    ) {
    }

    public function ask(Query $query): Response
    {
        throw new RuntimeException('Unknown query type: ' . $query::class);
    }
}
