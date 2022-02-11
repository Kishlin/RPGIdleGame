<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;
use Kishlin\Backend\Shared\Domain\Tools;
use Kishlin\Backend\Shared\Domain\ValueObject\IntValueObject;
use ReflectionException;

abstract class AbstractIntegerType extends IntegerType
{
    /**
     * @throws ReflectionException
     */
    public function getName(): string
    {
        $shortClassName = Tools::shortClassName($this->mappedClass());

        return Tools::fromPascalToSnakeCase($shortClassName);
    }

    /**
     * @param int $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): IntValueObject
    {
        $className = $this->mappedClass();

        return new $className($value);
    }

    /**
     * @param IntValueObject $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): int
    {
        return $value->value();
    }

    /**
     * @return class-string<IntValueObject>
     */
    abstract protected function mappedClass(): string;
}
