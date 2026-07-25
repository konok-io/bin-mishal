<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\FileLoader;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the database translation loader
        $this->app->singleton('translation.loader', function ($app) {
            return new DatabaseTranslationLoader(
                new FileLoader($app['files'], $app['path.lang'])
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

/**
 * Custom translation loader that checks database first, then falls back to file loader.
 */
class DatabaseTranslationLoader
{
    protected FileLoader $fileLoader;

    public function __construct(FileLoader $fileLoader)
    {
        $this->fileLoader = $fileLoader;
    }

    /**
     * Load the messages for the given locale.
     */
    public function load($locale, $group, $namespace = null): array
    {
        // First, load from files (base translations)
        $lines = $this->fileLoader->load($locale, $group, $namespace);

        // Then, overlay with database translations (higher priority)
        try {
            if (class_exists(\App\Models\Translation::class)) {
                $dbTranslations = \App\Models\Translation::getCachedForLocale($locale);

                // Filter for the requested group
                $groupPrefix = $namespace ? "{$namespace}::" : "";
                $groupPrefix .= $group . ".";

                foreach ($dbTranslations as $key => $value) {
                    if (str_starts_with($key, $groupPrefix)) {
                        $shortKey = substr($key, strlen($groupPrefix));

                        // Skip array values - only use string values
                        if (is_array($value)) {
                            continue;
                        }

                        if (str_contains($shortKey, '.')) {
                            data_set($lines, $shortKey, $value);
                        } else {
                            if (!isset($lines[$shortKey]) || $lines[$shortKey] === $shortKey) {
                                $lines[$shortKey] = $value;
                            }
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // If database is not available, just use file translations
        }

        // Ensure top-level values are strings (not arrays)
        return $this->ensureStringValues($lines);
    }

    /**
     * Ensure all top-level values in the array are strings.
     * If a value is an array, replace it with its first string value or the key.
     */
    protected function ensureStringValues(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                // For arrays, try to get the first string value
                $found = $this->findFirstString($value);
                $result[$key] = $found ?? $key;
            } else {
                $result[$key] = (string) $value;
            }
        }
        return $result;
    }

    /**
     * Recursively find the first string value in an array.
     */
    protected function findFirstString(mixed $value): ?string
    {
        if (is_string($value)) {
            return $value;
        }
        if (is_array($value)) {
            foreach ($value as $item) {
                $found = $this->findFirstString($item);
                if ($found !== null) {
                    return $found;
                }
            }
        }
        return null;
    }

    /**
     * Add a new namespace to the loader.
     */
    public function addNamespace($namespace, $hint): void
    {
        $this->fileLoader->addNamespace($namespace, $hint);
    }

    /**
     * Add a new JSON namespace to the loader.
     */
    public function addJsonPath($hint): void
    {
        $this->fileLoader->addJsonPath($hint);
    }

    /**
     * Get an array of all the registered namespaces.
     */
    public function namespaces(): array
    {
        return $this->fileLoader->namespaces();
    }
}
