<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Translation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class TranslationsSync extends Command
{
    protected $signature = 'translations:sync 
                            {--check : Exit with non-zero if any keys are missing translations}
                            {--group= : Only sync a specific translation group}
                            {--fresh : Mark all keys as not seen before re-syncing}';

    protected $description = 'Scan codebase for translation keys and sync with database';

    public function handle(): int
    {
        $this->info("========================================");
        $this->info(" TRANSLATION SYNC — " . now()->toDateTimeString());
        $this->info("========================================\n");

        // Clear cache if fresh flag is set
        if ($this->option('fresh')) {
            Translation::query()->update(['last_seen_in_code_at' => null]);
            Translation::clearCache();
            $this->warn("Cleared last_seen timestamps for all translations.\n");
        }

        // Scan for translation keys in code
        $keys = $this->scanForTranslationKeys();
        $this->info("Found " . count($keys) . " translation keys in code.\n");

        $added = 0;
        $updated = 0;
        $seen = 0;

        $this->output->progressStart(count($keys));

        foreach ($keys as $key) {
            $this->output->progressAdvance();
            
            // Parse key into group and key name
            [$group, $keyName] = $this->parseKey($key);
            
            // Filter by group if specified
            if ($this->option('group') && $group !== $this->option('group')) {
                continue;
            }

            $translation = Translation::findOrCreateByKey($group, $keyName);
            
            // Update last_seen timestamp
            if (!$translation->last_seen_in_code_at) {
                $translation->last_seen_in_code_at = now();
                $added++;
            } else {
                $seen++;
            }
            
            // Auto-detect missing locales and update status
            $translation->updateStatus();
            $translation->save();
            $updated++;
        }

        $this->output->progressFinish();

        // Mark translations no longer in code
        $this->markUnusedTranslations($keys);

        // Clear translation cache
        Translation::clearCache();
        $this->info("\nCleared translation cache.\n");

        // Summary
        $this->info("--- SUMMARY ---");
        $this->table(
            ['Metric', 'Count'],
            [
                ['New keys found', $added],
                ['Previously seen', $seen],
                ['Total synced', $updated],
            ]
        );

        // Report missing translations
        $incomplete = Translation::incomplete();
        
        if ($this->option('group')) {
            $incomplete->forGroup($this->option('group'));
        }

        $incompleteCount = $incomplete->count();

        if ($incompleteCount > 0) {
            $this->warn("\n❌ {$incompleteCount} translations are missing translations:\n");
            
            $missingRows = $incomplete->limit(20)->get(['group', 'key', 'status']);
            $tableData = $missingRows->map(fn($t) => [
                "{$t->group}.{$t->key}",
                $t->status
            ])->toArray();
            
            $this->table(['Key', 'Status'], $tableData);
            
            if ($incompleteCount > 20) {
                $this->line("... and " . ($incompleteCount - 20) . " more missing translations");
            }
        }

        // Check mode: exit non-zero if missing translations
        if ($this->option('check') && $incompleteCount > 0) {
            $this->error("\n❌ BUILD FAILED: {$incompleteCount} translation keys are missing one or more language values.");
            $this->line("   Run `php artisan translations:sync` to see the full list.");
            $this->line("   Add `--group=<name>` to filter by translation group.");
            return 1;
        }

        $this->info("\n✅ Translation sync complete.");
        return 0;
    }

    /**
     * Scan the codebase for translation keys.
     */
    protected function scanForTranslationKeys(): array
    {
        $keys = [];

        $patterns = [
            // __('key') and __("key")
            '/__\([\'"]([^\'"]+)[\'"]\)/',
            // trans('key')
            '/trans\([\'"]([^\'"]+)[\'"]\)/',
            // @lang('key')
            '/@lang\([\'"]([^\'"]+)[\'"]\)/',
            // trans_choice('key', $count)
            '/trans_choice\([\'"]([^\'"]+)[\'"]\)/',
            // :attribute in validation messages
            '/:attribute/',
            // Validation rule keys
            '/\'[a-z_]+\' => /',
        ];

        $extensions = ['blade.php', '.php'];
        $directories = [
            base_path('resources/views'),
            base_path('app/Livewire'),
            base_path('app/Notifications'),
            base_path('app/Mail'),
            base_path('app/Http/Requests'),
            base_path('resources/lang'),
        ];

        // Also scan validation.php and auth.php for common keys
        $this->scanLangFile(base_path('lang/en/validation.php'), 'validation', $keys);
        $this->scanLangFile(base_path('lang/en/auth.php'), 'auth', $keys);
        $this->scanLangFile(base_path('lang/en/passwords.php'), 'passwords', $keys);

        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                continue;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                if (!in_array($file->getExtension(), $extensions)) {
                    continue;
                }

                $content = file_get_contents($file->getPathname());

                foreach ($patterns as $pattern) {
                    if (preg_match_all($pattern, $content, $matches)) {
                        foreach ($matches[1] as $key) {
                            // Skip dynamic keys (containing variables)
                            if (strpos($key, '$') !== false || strpos($key, ':') === 0) {
                                continue;
                            }
                            
                            // Skip if already added
                            if (!in_array($key, $keys)) {
                                $keys[] = $key;
                            }
                        }
                    }
                }
            }
        }

        return array_unique($keys);
    }

    /**
     * Scan a lang file for keys.
     */
    protected function scanLangFile(string $path, string $group, array &$keys): void
    {
        if (!file_exists($path)) {
            return;
        }

        $content = include $path;
        if (!is_array($content)) {
            return;
        }

        $this->flattenArray($content, '', $group, $keys);
    }

    /**
     * Flatten a nested array into dot-notation keys.
     */
    protected function flattenArray(array $array, string $prefix, string $group, array &$keys): void
    {
        foreach ($array as $key => $value) {
            $fullKey = $prefix ? "{$prefix}.{$key}" : $key;
            
            if (is_array($value)) {
                $this->flattenArray($value, $fullKey, $group, $keys);
            } else {
                $keys[] = "{$group}.{$fullKey}";
            }
        }
    }

    /**
     * Parse a translation key into group and key name.
     */
    protected function parseKey(string $key): array
    {
        if (str_contains($key, '::')) {
            // Namespaced key like "validation.required"
            $parts = explode('::', $key);
            $namespace = array_shift($parts);
            $keyName = implode('.', $parts);
            return [$namespace, $keyName];
        }

        if (str_contains($key, '.')) {
            // Grouped key like "validation.required"
            $parts = explode('.', $key);
            $group = array_shift($parts);
            $keyName = implode('.', $parts);
            return [$group, $keyName];
        }

        // Simple key without group
        return ['custom', $key];
    }

    /**
     * Mark translations that are no longer in code.
     */
    protected function markUnusedTranslations(array $usedKeys): void
    {
        // Find translations that haven't been seen in code recently
        // This is informational only - we don't delete them
        $unusedCount = Translation::whereNull('last_seen_in_code_at')
            ->orWhere('last_seen_in_code_at', '<', now()->subDays(30))
            ->count();

        if ($unusedCount > 0) {
            $this->warn("\nℹ️  {$unusedCount} translations are not currently used in code (last seen > 30 days ago).");
            $this->line("   These are kept in the database but flagged as unused.");
        }
    }
}
