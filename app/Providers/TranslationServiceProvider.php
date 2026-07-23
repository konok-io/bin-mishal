<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Translation;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\LoaderInterface;
use Illuminate\Translation\Translator;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider implements ServiceProvider
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

        // Override the translator to use our loader
        $this->app->singleton(Translator::class, function ($app) {
            $translator = new Translator($app['translation.loader'], $app['config']['app.locale']);
            $translator->setFallback($app['config']['app.fallback_locale']);
            return $translator;
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Clear translation cache when a translation is saved
        Translation::clearCache();
    }
}

/**
 * Custom translation loader that checks database first, then falls back to file loader.
 */
class DatabaseTranslationLoader implements LoaderInterface
{
    protected FileLoader $fileLoader;

    public function __construct(FileLoader $fileLoader)
    {
        $this->fileLoader = $fileLoader;
    }

    /**
     * Load the messages for the given locale.
     *
     * @param string $locale
     * @param string $group
     * @param string|null $namespace
     * @return array
     */
    public function load($locale, $group, $namespace = null): array
    {
        // First, load from files (base translations)
        $lines = $this->fileLoader->load($locale, $group, $namespace);

        // Then, overlay with database translations (higher priority)
        $dbTranslations = Translation::getCachedForLocale($locale);

        // Filter for the requested group
        $groupPrefix = $namespace ? "{$namespace}::" : "";
        $groupPrefix .= $group . ".";

        foreach ($dbTranslations as $key => $value) {
            // Check if this key belongs to the requested group
            if (str_starts_with($key, $groupPrefix)) {
                // Extract the key without the group prefix
                $shortKey = substr($key, strlen($groupPrefix));
                
                // If it's a nested key (contains dots), we need to build the array
                if (str_contains($shortKey, '.')) {
                    data_set($lines, $shortKey, $value);
                } else {
                    // Simple key
                    if (!isset($lines[$shortKey]) || $lines[$shortKey] === $shortKey) {
                        // Only override if the file translation is missing or is the raw key
                        $lines[$shortKey] = $value;
                    }
                }
            }
        }

        return $lines;
    }

    /**
     * Add a new namespace to the loader.
     *
     * @param string $namespace
     * @param string $hint
     * @return void
     */
    public function addNamespace($namespace, $hint): void
    {
        $this->fileLoader->addNamespace($namespace, $hint);
    }

    /**
     * Add a new JSON namespace to the loader.
     *
     * @param string $namespace
     * @param string $hint
     * @return void
     */
    public function addJsonPath($hint): void
    {
        $this->fileLoader->addJsonPath($hint);
    }

    /**
     * Get an array of all the registered namespaces.
     *
     * @return array
     */
    public function namespaces(): array
    {
        return $this->fileLoader->namespaces();
    }
}
