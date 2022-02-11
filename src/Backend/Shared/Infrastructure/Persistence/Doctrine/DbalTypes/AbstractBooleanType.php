<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\DbalTypes;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BooleanType;
use Kishlin\Backend\Shared\Domain\Tools;
use Kishlin\Backend\Shared\Domain\ValueObject\BoolValueObject;
use ReflectionException;

abstract class AbstractBooleanType extends BooleanType
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
     * @param bool $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): BoolValueObject
    {
        $className = $this->mappedClass();

        return new $className($value);
    }

    /**
     * @param BoolValueObject $value
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): bool
    {
        return $value->value();
    }

    /**
     * @return class-string<BoolValueObject>
     */
    abstract protected function mappedClass(): string;
}
