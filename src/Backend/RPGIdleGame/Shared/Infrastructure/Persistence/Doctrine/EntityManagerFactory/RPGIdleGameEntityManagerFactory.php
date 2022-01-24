<?php

declare(strict_types=1);

namespace Kishlin\Backend\RPGIdleGame\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory;

use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory\DoctrineCustomTypeSearcher;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory\DoctrineEntityManagerFactory;
use Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory\DoctrinePrefixesSearcher;
use ReflectionException;

/**
 * Used as a factory to instantiate the Doctrine EntityManager with the mapping configuration and Dbal Types.
 *
 * All Namespaces live in the src/Backend/{RPGIdleGame, Shared, ...}/ folders and are scanned separately.
 * In each Namespace, the Searchers will scan modules, and look for Doctrine configuration files inside.
 *
 * Mappings always live in the 'Infrastructure/Persistence/Doctrine/Mapping' path, inside a module.
 * DbalTypes always live in the 'Infrastructure/Persistence/Doctrine/DbalTypes' path, inside a module.
 * It is critical that Doctrine configuration files live in these paths. Any file that does not, will not be scanned.
 *
 * @see \Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory\DoctrinePrefixesSearcher
 * @see \Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory\DoctrineCustomTypeSearcher
 * @see \Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory\DoctrineEntityManagerFactory
 */
final class RPGIdleGameEntityManagerFactory
{
    /**
     * @param array<string, string> $parameters
     *
     * @throws Exception|ORMException|ReflectionException
     */
    public static function create(array $parameters, string $environment): EntityManagerInterface
    {
        $isDevMod = 'prod' !== $environment;

        $prefixes = $dbalCustomTypes = [];

        foreach (self::configurationData() as $configurationDatum) {
            $prefixes = array_merge(
                DoctrinePrefixesSearcher::inPath(
                    mainPath: $configurationDatum['mainPath'],
                    mappingsPath: $configurationDatum['mappingsPath'],
                    baseNamespace: $configurationDatum['baseNamespace'],
                    hasModules: $configurationDatum['hasModules'],
                ),
                $prefixes,
            );

            $dbalCustomTypes = array_merge(
                DoctrineCustomTypeSearcher::inPath(
                    mainPath: $configurationDatum['mainPath'],
                    typesPath: $configurationDatum['typesPath'],
                    baseNamespace: $configurationDatum['baseNamespace'],
                    hasModules: $configurationDatum['hasModules'],
                ),
                $dbalCustomTypes,
            );
        }

        return DoctrineEntityManagerFactory::create($parameters, $prefixes, $dbalCustomTypes, $isDevMod);
    }

    /**
     * @phpstan-ignore-next-line
     */
    private static function configurationData(): iterable
    {
        yield [
            'mainPath'      => __DIR__ . '/../../../../../../../../src/Backend/RPGIdleGame',
            'mappingsPath'  => 'Infrastructure/Persistence/Doctrine/Mapping',
            'typesPath'     => 'Infrastructure/Persistence/Doctrine/DbalTypes',
            'baseNamespace' => 'Kishlin\Backend\RPGIdleGame',
            'hasModules'    => true,
        ];

        yield [
            'mainPath'      => __DIR__ . '/../../../../../../../../src/Backend/Account',
            'mappingsPath'  => 'Infrastructure/Persistence/Doctrine/Mapping',
            'typesPath'     => 'Infrastructure/Persistence/Doctrine/DbalTypes',
            'baseNamespace' => 'Kishlin\Backend\Account',
            'hasModules'    => false,
        ];

        yield [
            'mainPath'      => __DIR__ . '/../../../../../../../../src/Backend/Shared',
            'mappingsPath'  => 'Infrastructure/Persistence/Doctrine/Mapping',
            'typesPath'     => 'Infrastructure/Persistence/Doctrine/DbalTypes',
            'baseNamespace' => 'Kishlin\Backend\Shared',
            'hasModules'    => false,
        ];
    }
}
