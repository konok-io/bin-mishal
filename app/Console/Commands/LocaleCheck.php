<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class LocaleCheck extends Command
{
    protected $signature = 'locale:check 
                            {--check : Exit with non-zero if any used key is missing from any locale}
                            {--audit : Scan all views for translation usage}';

    protected $description = 'Check locale configuration and translation file consistency, audit views for missing keys';

    public function handle(): int
    {
        $locales = array_keys(config('locales.enabled', []));
        $langDir = base_path('lang');
        $hasIssues = false;

        $this->info("========================================");
        $this->info(" LOCALE CHECK — " . now()->toDateTimeString());
        $this->info("========================================\n");

        // === PART 1: Collect keys from lang files ===
        $allDefinedKeys = [];
        $allFiles = [];
        
        foreach ($locales as $locale) {
            $dir = "{$langDir}/{$locale}";
            if (is_dir($dir)) {
                $files = glob("{$dir}/*.php") ?: [];
                foreach ($files as $f) {
                    $allFiles[basename($f)] = true;
                }
            }
        }
        $allFiles = array_keys($allFiles);

        // Collect all keys per locale
        $localeKeys = [];
        foreach ($locales as $locale) {
            $localeKeys[$locale] = $this->collectKeys("{$langDir}/{$locale}");
            foreach ($localeKeys[$locale] as $group => $keys) {
                if (!isset($allDefinedKeys[$group])) {
                    $allDefinedKeys[$group] = [];
                }
                $allDefinedKeys[$group] = array_merge($allDefinedKeys[$group], $keys);
            }
        }

        // === PART 2: Scan views for translation usage ===
        if ($this->option('audit') || $this->option('check')) {
            $this->info("--- SCANNING VIEWS FOR TRANSLATION USAGE ---\n");
            $usedKeys = $this->scanViewsForTranslations();
            
            $this->info("Found " . count($usedKeys) . " unique translation keys in views.\n");
            
            // Check each locale for missing keys
            foreach ($locales as $locale) {
                $missing = [];
                $defined = $localeKeys[$locale] ?? [];
                
                foreach ($usedKeys as $key => $locations) {
                    if (!$this->keyExistsInLocale($key, $defined)) {
                        $missing[$key] = $locations;
                    }
                }
                
                if (!empty($missing)) {
                    $this->error("❌ {$locale}: " . count($missing) . " keys used in code but missing from lang/{$locale}/*.php");
                    $this->line("");
                    
                    $tableData = [];
                    foreach (array_slice($missing, 0, 20, true) as $key => $locs) {
                        $tableData[] = [
                            $key,
                            implode(", ", array_slice($locs, 0, 3)),
                            count($locs) > 3 ? "...+" . (count($locs) - 3) : ""
                        ];
                    }
                    
                    $this->table(['Missing Key', 'Used In (sample)', 'More'], $tableData);
                    
                    if (count($missing) > 20) {
                        $this->line("... and " . (count($missing) - 20) . " more missing keys");
                    }
                    $this->line("");
                    $hasIssues = true;
                } else {
                    $this->info("✅ {$locale}: All " . count($usedKeys) . " used keys are defined");
                }
            }
            
            // Report unused keys (informational only)
            $this->info("\n--- UNUSED KEYS (informational) ---");
            foreach ($allDefinedKeys as $group => $keys) {
                $unused = [];
                foreach ($keys as $key) {
                    if (!isset($usedKeys[$key])) {
                        $unused[] = $key;
                    }
                }
                if (!empty($unused)) {
                    $this->warn("  {$group}: " . count($unused) . " defined but not used in views");
                }
            }
        }

        // === PART 3: Locale comparison (existing behavior) ===
        $this->info("\n--- LOCALE COVERAGE COMPARISON ---");
        
        $refLocale = $locales[0] ?? null;
        $refKeys = $refLocale ? ($localeKeys[$refLocale] ?? []) : [];
        
        $headers = ['locale', 'dir', 'files', 'keys', 'vs ref', 'direction', 'font'];
        $rows = [];
        
        foreach ($locales as $locale) {
            $dir = "{$langDir}/{$locale}";
            $dirExists = is_dir($dir);
            $files = $dirExists ? (glob("{$dir}/*.php") ?: []) : [];
            $keyCount = 0;
            $missingKeys = [];
            
            if ($dirExists) {
                $keys = $localeKeys[$locale] ?? [];
                $keyCount = count($keys);
                
                if ($refLocale && $refLocale !== $locale) {
                    foreach ($refKeys as $group => $groupKeys) {
                        $localeGroupKeys = $localeKeys[$locale][$group] ?? [];
                        foreach ($groupKeys as $key) {
                            if (!in_array($key, $localeGroupKeys)) {
                                $missingKeys[] = "{$group}.{$key}";
                            }
                        }
                    }
                }
            }
            
            $config = config("locales.enabled.{$locale}", []);
            $direction = $config['direction'] ?? '?';
            $fontShort = '?';
            if (!empty($config['font_family'])) {
                $fontShort = Str::limit(preg_replace("/'/", '', $config['font_family']), 30);
            }
            
            $missingStr = $dirExists
                ? (empty($missingKeys) ? '✅ none' : '❌ ' . implode(', ', array_slice($missingKeys, 0, 3)) . (count($missingKeys) > 3 ? ' ...' : ''))
                : '❌ MISSING';
            
            $status = !$dirExists || !empty($missingKeys) ? '❌' : '✅';
            if (!$dirExists || !empty($missingKeys)) {
                $hasIssues = true;
            }
            
            $rows[] = [
                $status . ' ' . $locale,
                $dirExists ? '✅' : '❌',
                (string) count($files),
                (string) $keyCount,
                $missingStr,
                $direction,
                $fontShort,
            ];
        }
        
        $this->table($headers, $rows);

        // === PART 4: Config checks ===
        $this->info("\n--- CONFIG CHECKS ---");
        $checks = [
            'APP_LOCALE env' => config('app.locale'),
            'APP_FALLBACK_LOCALE' => config('app.fallback_locale'),
            'Carbon locale' => \Carbon\Carbon::getLocale(),
            'Default from locales.php' => config('locales.default'),
            'Fallback from locales.php' => config('locales.fallback'),
            'Lang directory path' => $langDir,
            'Lang directory readable' => is_dir($langDir) && is_readable($langDir) ? '✅' : '❌',
        ];
        
        foreach ($checks as $label => $value) {
            $this->line("  {$label}: <info>{$value}</info>");
        }

        // Enforce: effective default locale must be bn
        $effectiveDefault = config('app.locale');
        if ($effectiveDefault !== 'bn') {
            $this->error("\n  ❌ FATAL: Effective default locale is '{$effectiveDefault}', expected 'bn'.");
            $hasIssues = true;
        } else {
            $this->info("\n  ✅ Effective default locale is 'bn'.");
        }

        // === SUMMARY ===
        $this->info("\n========================================");
        if ($hasIssues) {
            $this->error("❌ ISSUES FOUND — see above for details");
            if ($this->option('check')) {
                return 1;
            }
        } else {
            $this->info("✅ ALL CHECKS PASSED");
        }
        
        return $hasIssues && $this->option('check') ? 1 : 0;
    }

    /**
     * Collect all translation keys from a locale directory.
     */
    protected function collectKeys(string $dir, ?string $filename = null): array
    {
        $keys = [];
        $files = $filename
            ? ["{$dir}/{$filename}"]
            : (glob("{$dir}/*.php") ?: []);

        foreach ($files as $file) {
            if (!file_exists($file)) {
                continue;
            }
            $content = include $file;
            if (!is_array($content)) {
                continue;
            }
            
            $group = pathinfo($file, PATHINFO_FILENAME);
            $flatKeys = [];
            array_walk_recursive($content, function ($v, $k) use (&$flatKeys) {
                $flatKeys[] = $k;
            });
            
            $keys[$group] = $flatKeys;
        }

        return $keys;
    }

    /**
     * Scan all views for translation key usage.
     */
    protected function scanViewsForTranslations(): array
    {
        $usedKeys = [];
        
        $patterns = [
            // __('key') and __("key")
            '/__\([\'"]([^\'"]+)[\'"]\)/',
            // trans('key')
            '/trans\([\'"]([^\'"]+)[\'"]\)/',
            // @lang('key')
            '/@lang\([\'"]([^\'"]+)[\'"]\)/',
            // trans_choice('key', $count)
            '/trans_choice\([\'"]([^\'"]+)[\'"]\)/',
        ];
        
        $extensions = ['blade.php', '.php'];
        $directories = [
            base_path('resources/views'),
            base_path('app/Livewire'),
            base_path('app/Notifications'),
        ];
        
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
                
                $path = str_replace(base_path() . '/', '', $file->getPathname());
                $content = file_get_contents($file->getPathname());
                
                foreach ($patterns as $pattern) {
                    if (preg_match_all($pattern, $content, $matches)) {
                        foreach ($matches[1] as $key) {
                            // Skip nested arrays like __('key') where key is a variable
                            if (strpos($key, '$') !== false) {
                                continue;
                            }
                            
                            if (!isset($usedKeys[$key])) {
                                $usedKeys[$key] = [];
                            }
                            $usedKeys[$key][] = $path;
                        }
                    }
                }
            }
        }
        
        return $usedKeys;
    }

    /**
     * Check if a key exists in the locale's keys.
     */
    protected function keyExistsInLocale(string $key, array $localeKeys): bool
    {
        // Key format: "group.key" or just "key"
        if (str_contains($key, '.')) {
            [$group, $keyName] = explode('.', $key, 2);
            return isset($localeKeys[$group]) && in_array($keyName, $localeKeys[$group]);
        }
        
        // Check all groups
        foreach ($localeKeys as $group => $keys) {
            if (in_array($key, $keys)) {
                return true;
            }
        }
        
        return false;
    }
}
