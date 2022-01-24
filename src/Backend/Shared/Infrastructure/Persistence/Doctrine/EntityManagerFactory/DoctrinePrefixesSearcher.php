<?php

declare(strict_types=1);

namespace Kishlin\Backend\Shared\Infrastructure\Persistence\Doctrine\EntityManagerFactory;

final class DoctrinePrefixesSearcher
{
    /**
     * Finds all mapping files inside every module of a given path.
     * Returns an array that associates Namespaces to where its mapping files are on the filesystem.
     * [ 'path/to/mapping/files' => '\Mapped\Namespace' ].
     *
     * @param string $mainPath      the filesystem path in which to scan for modules
     * @param string $mappingsPath  the filesystem path to mapping files inside a module
     * @param string $baseNamespace the Namespace associated to the folder targeted by $path
     * @param bool   $hasModules    whether the namespace has modules, resulting in an additional folder to scan
     *
     * @return array<string, string> Keys are the path on the filesystem, values are the mapped Namespace
     */
    public static function inPath(string $mainPath, string $mappingsPath, string $baseNamespace, bool $hasModules): array
    {
        if (false === $hasModules) {
            $dir = "{$mainPath}/{$mappingsPath}";

            return true === is_dir($dir) ? [$dir => "{$baseNamespace}\\Domain"] : [];
        }

        $prefixesPathsToNamespaces = [];

        foreach (self::modulesInPath($mainPath) as $module) {
            $prefixesPath = "{$mainPath}/{$module}/{$mappingsPath}";

            if (is_dir($prefixesPath)) {
                $prefixesPathsToNamespaces[$prefixesPath] = "{$baseNamespace}\\{$module}\\Domain";
            }
        }

        return $prefixesPathsToNamespaces;
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

        return array_diff($dirContent, ['..', '.', 'Shared']); // No Mapping files inside the Shared module.
    }
}
