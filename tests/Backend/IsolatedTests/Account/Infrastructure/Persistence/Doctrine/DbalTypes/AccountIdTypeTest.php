<?php

declare(strict_types=1);

namespace Kishlin\Tests\Backend\IsolatedTests\Account\Infrastructure\Persistence\Doctrine\DbalTypes;

use Kishlin\Backend\Account\Domain\AccountId;
use Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\DbalTypes\AccountIdType;
use Kishlin\Tests\Backend\Tools\Test\Isolated\UuidTypeIsolatedTestCase;
use ReflectionException;

/**
 * @internal
 * @covers \Kishlin\Backend\Account\Infrastructure\Persistence\Doctrine\DbalTypes\AccountIdType
 */
final class AccountIdTypeTest extends UuidTypeIsolatedTestCase
{
    /**
     * @throws ReflectionException
     */
    public function testTypeNameIsAsExpected(): void
    {
        $this->assertTypeNameIs('account_id', new AccountIdType());
    }

    public function testItCanConvertToDatabaseValue(): void
    {
        $uuid = 'cceeb942-e48e-41b0-ae44-ead311fdbbb3';

        $this->assertItIsConvertedToDatabaseValue($uuid, new AccountId($uuid), new AccountIdType());
    }

    public function testItCanConvertToPhpValue(): void
    {
        $uuid = 'cceeb942-e48e-41b0-ae44-ead311fdbbb3';

        $this->assertIsConvertedToPhpValue(AccountId::class, $uuid, new AccountIdType());
    }
}
