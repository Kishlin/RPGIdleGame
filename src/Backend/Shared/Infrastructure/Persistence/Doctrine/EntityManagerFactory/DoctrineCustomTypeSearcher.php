<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory;

use Doctrine\DBAL\Types\Type;
use Kishlin\Backend\Shared\Domain\Tools;

final class DoctrineCustomTypeSearcher
{
    /**
     * Finds all custom DbalTypes inside every module of a given path.
     *
     * @param string $mainPath      the filesystem path in which to scan for modules
     * @param string $typesPath     the filesystem path to custom Dbal types inside a module
     * @param string $baseNamespace the Namespace associated to the folder targeted by $path
     * @param bool   $hasModules    whether the namespace has modules, resulting in an additional folder to scan
     *
     * @return array<class-string<Type>> class names of all custom Dbal Types found in every module inside $path
     */
    public static function inPath(string $mainPath, string $typesPath, string $baseNamespace, bool $hasModules): array
    {
        if (false === $hasModules) {
            return self::prefixesInPath($mainPath, $typesPath, $baseNamespace);
        }

        $typesInPath = [];

        foreach (self::modulesInPath($mainPath) as $module) {
            $typesInPath = array_merge(
                self::prefixesInPath($mainPath, "{$module}/{$typesPath}", $baseNamespace),
                $typesInPath,
            );
        }

        return $typesInPath;
    }

    /**
     * @return array<int, string>
     */
    private static function modulesInPath(string $path): array
    {
        $dirContent = scandir($path);

        if (false === $dirContent) {
            return [];
        }

        return array_diff($dirContent, ['..', '.', 'Shared']); // No DbalTypes inside the Shared module.
    }

    /**
     * @return array<class-string<Type>>
     */
    private static function prefixesInPath(string $mainPath, string $typesPath, string $baseNamespace): array
    {
        $pathToTypes = "{$mainPath}/{$typesPath}";
        if (false === is_dir($pathToTypes)) {
            return [];
        }

        $typesDirContent = scandir($pathToTypes);
        if (false === $typesDirContent) {
            return [];
        }

        $prefixesInPath = [];
        foreach ($typesDirContent as $file) {
            if (false === self::fileIsAConcreteDbalTypeClass($file)) {
                continue;
            }

            /** @var class-string<Type> $typeClass */
            $typeClass = self::fullyQualifiedDbalTypeClassName($baseNamespace, $typesPath, $file);

            $prefixesInPath[] = $typeClass;
        }

        return $prefixesInPath;
    }

    private static function fileIsAConcreteDbalTypeClass(string $file): bool
    {
        return Tools::endsWith($file, 'Type.php') && false === Tools::startsWith($file, 'Abstract');
    }

    private static function fullyQualifiedDbalTypeClassName(string $namespace, string $typesPath, string $file): string
    {
        $filePathInNamespace = "{$namespace}/{$typesPath}/{$file}";

        return str_replace(['/', '.php'], ['\\', ''], $filePathInNamespace);
    }
}
