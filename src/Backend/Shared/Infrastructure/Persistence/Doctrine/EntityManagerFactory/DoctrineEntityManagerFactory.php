<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory;

use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Setup;
use ReflectionException;

/**
 * Used as a factory to instantiate the Doctrine EntityManager with the mapping configuration and Dbal Types.
 */
final class DoctrineEntityManagerFactory
{
    /**
     * @param array<string, string>     $parameters             must include the connection configuration required by Doctrine
     * @param array<string, string>     $xmlMappingFiles
     * @param array<class-string<Type>> $dbalCustomTypesClasses
     *
     * @throws Exception|ORMException|ReflectionException
     */
    public static function create(
        array $parameters,
        array $xmlMappingFiles,
        array $dbalCustomTypesClasses,
        bool $isDevMode
    ): EntityManagerInterface {
        DbalCustomTypesRegistrar::register($dbalCustomTypesClasses);

        return EntityManager::create($parameters, self::createDoctrineConfiguration($xmlMappingFiles, $isDevMode));
    }

    /**
     * @param array<string, string> $xmlMappingFiles
     */
    private static function createDoctrineConfiguration(array $xmlMappingFiles, bool $isDevMode): Configuration
    {
        $config = Setup::createConfiguration($isDevMode, null, null);

        $config->setMetadataDriverImpl(new SimplifiedXmlDriver($xmlMappingFiles));

        return $config;
    }
}
