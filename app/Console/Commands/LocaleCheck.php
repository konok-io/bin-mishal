<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class LocaleCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'locale:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check locale configuration and translation file consistency';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $locales = array_keys(config('locales.enabled', []));
        $langDir = base_path('lang');
        $hasIssues = false;

        $this->info("========================================");
        $this->info(" LOCALE CHECK — " . now()->toDateTimeString());
        $this->info("========================================\n");

        // Header row
        $headers = ['locale', 'dir exists', 'files', 'keys', 'missing vs ref', 'direction', 'font'];
        $rows = [];

        // Collect reference keys from first locale
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

        // Reference keys (from first locale)
        $refLocale = $locales[0] ?? null;
        $refKeys = $this->collectKeys("{$langDir}/{$refLocale}");

        foreach ($locales as $locale) {
            $dir = "{$langDir}/{$locale}";
            $dirExists = is_dir($dir);
            $files = $dirExists ? (glob("{$dir}/*.php") ?: []) : [];
            $keyCount = 0;
            $missingKeys = [];

            if ($dirExists) {
                $localeKeys = $this->collectKeys($dir);
                $keyCount = count($localeKeys);

                if ($refLocale && $refLocale !== $locale) {
                    $missingKeys = array_diff($refKeys, $localeKeys);
                }
            }

            $config = config("locales.enabled.{$locale}", []);
            $direction = $config['direction'] ?? '?';
            $fontShort = '?';
            if (!empty($config['font_family'])) {
                $fontShort = Str::limit(preg_replace("/'/", '', $config['font_family']), 30);
            }

            $missingStr = $dirExists
                ? (empty($missingKeys) ? '✅ none' : '❌ ' . implode(', ', array_slice($missingKeys, 0, 5)) . (count($missingKeys) > 5 ? ' ...(' . count($missingKeys) . ' total)' : ''))
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

        // File-level check
        $this->info("\n--- FILE COVERAGE ---");
        foreach ($allFiles as $filename) {
            $this->line("  {$filename}:");
            foreach ($locales as $locale) {
                $file = "{$langDir}/{$locale}/{$filename}";
                $exists = file_exists($file);
                $keys = $exists ? count($this->collectKeys("{$langDir}/{$locale}", $filename)) : 0;
                $icon = $exists ? '✅' : '❌';
                $this->line("    {$icon} {$locale}: {$keys} keys");
                if (!$exists) {
                    $hasIssues = true;
                }
            }
        }

        // Config checks
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
            $this->line("     config/app.php  → locale => env('APP_LOCALE', 'bn')");
            $this->line("     config/locales.php → default => env('APP_LOCALE', 'bn')");
            $this->line("     .env.example     → APP_LOCALE=bn");
            $hasIssues = true;
        } else {
            $this->info("\n  ✅ Effective default locale is 'bn'.");
        }

        // Enabled locales detail
        $this->info("\n--- ENABLED LOCALES ---");
        $enabledHeaders = ['code', 'name', 'native', 'dir', 'number_system', 'enabled'];
        $enabledRows = [];
        foreach (config('locales.enabled', []) as $code => $cfg) {
            $enabledRows[] = [
                $code,
                $cfg['name'] ?? '?',
                $cfg['native_name'] ?? '?',
                $cfg['direction'] ?? '?',
                $cfg['number_system'] ?? '?',
                ($cfg['enabled'] ?? false) ? '✅' : '❌',
            ];
        }
        $this->table($enabledHeaders, $enabledRows);

        // Summary
        $this->info("\n========================================");
        if ($hasIssues) {
            $this->error("❌ ISSUES FOUND — see above for details");
            return 1;
        } else {
            $this->info("✅ ALL CHECKS PASSED — default locale is 'bn', all locales complete.");
            return 0;
        }
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
            $flat = [];
            array_walk_recursive($content, function ($v, $k) use (&$flat) {
                $flat[] = $k;
            });
            $keys = array_merge($keys, $flat);
        }

        return array_unique($keys);
    }
}
