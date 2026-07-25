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

                        // Ensure value is a string
                        $value = (string) $value;

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

        // Ensure ALL values are strings recursively
        return $this->stringifyArray($lines);
    }

    /**
     * Recursively convert all values in an array to strings.
     * Nested arrays become the first string value found or the key name.
     */
    protected function stringifyArray(array $arr): array
    {
        $result = [];
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                // Recursively process nested arrays
                $result[$key] = $this->stringifyValue($value) ?? (string) $key;
            } else {
                $result[$key] = (string) $value;
            }
        }
        return $result;
    }

    /**
     * Convert an array to a string by finding the first string value.
     */
    protected function stringifyValue(array $arr): ?string
    {
        foreach ($arr as $value) {
            if (is_string($value)) {
                return $value;
            }
            if (is_array($value)) {
                $result = $this->stringifyValue($value);
                if ($result !== null) {
                    return $result;
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
