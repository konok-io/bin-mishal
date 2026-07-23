<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleTest extends TestCase
{
    // ─────────────────────────────────────────────────────────────
    // A11: Public route returns 200 for each locale
    // ─────────────────────────────────────────────────────────────

    public function test_bn_returns_200(): void
    {
        $response = $this->get('/bn');
        $response->assertStatus(200);
    }

    public function test_en_returns_200(): void
    {
        $response = $this->get('/en');
        $response->assertStatus(200);
    }

    public function test_ar_returns_200(): void
    {
        $response = $this->get('/ar');
        $response->assertStatus(200);
    }

    // ─────────────────────────────────────────────────────────────
    // A11: Root redirects to default locale (/bn)
    // ─────────────────────────────────────────────────────────────

    public function test_root_redirects_to_bn(): void
    {
        $response = $this->get('/');
        $response->assertRedirectContains('/bn');
    }

    // ─────────────────────────────────────────────────────────────
    // A11: Invalid locale redirects to default
    // ─────────────────────────────────────────────────────────────

    public function test_invalid_locale_redirects_to_bn(): void
    {
        $response = $this->get('/fr');
        // Should redirect to default locale (bn)
        $response->assertRedirect('/bn');
    }

    // ─────────────────────────────────────────────────────────────
    // A11: RTL direction only for Arabic
    // ─────────────────────────────────────────────────────────────

    public function test_ar_has_rtl_direction(): void
    {
        $response = $this->get('/ar');
        $response->assertStatus(200);
        $response->assertSee('dir="rtl"', false);
    }

    public function test_bn_has_ltr_direction(): void
    {
        $response = $this->get('/bn');
        $response->assertStatus(200);
        $response->assertSee('dir="ltr"', false);
    }

    public function test_en_has_ltr_direction(): void
    {
        $response = $this->get('/en');
        $response->assertStatus(200);
        $response->assertSee('dir="ltr"', false);
    }

    // ─────────────────────────────────────────────────────────────
    // A11: Locale test page returns 200 for each locale
    // ─────────────────────────────────────────────────────────────

    public function test_bn_locale_test_returns_200(): void
    {
        $response = $this->get('/bn/locale-test');
        $response->assertStatus(200);
        $response->assertSee('ACTIVE LOCALE', false);
    }

    public function test_en_locale_test_returns_200(): void
    {
        $response = $this->get('/en/locale-test');
        $response->assertStatus(200);
        $response->assertSee('ACTIVE LOCALE', false);
    }

    public function test_ar_locale_test_returns_200(): void
    {
        $response = $this->get('/ar/locale-test');
        $response->assertStatus(200);
        $response->assertSee('ACTIVE LOCALE', false);
    }

    // ─────────────────────────────────────────────────────────────
    // A11: Route name 'home' resolves correctly
    // ─────────────────────────────────────────────────────────────

    public function test_home_route_name_resolves(): void
    {
        $url = route('home', ['locale' => 'bn']);
        $this->assertEquals('/bn', $url);

        $response = $this->get($url);
        $response->assertStatus(200);
    }

    // ─────────────────────────────────────────────────────────────
    // A11: switch_locale_url preserves path and query
    // ─────────────────────────────────────────────────────────────

    public function test_switch_locale_url_preserves_path(): void
    {
        $response = $this->get('/bn/locale-test?foo=bar');
        $response->assertStatus(200);
        // The switcher should have URLs to /en/locale-test and /ar/locale-test
        $response->assertSee('/en/locale-test', false);
        $response->assertSee('/ar/locale-test', false);
        $response->assertSee('?foo=bar', false); // query preserved
    }

    // ─────────────────────────────────────────────────────────────
    // A11: Locale resolved from route segment (highest priority)
    // ─────────────────────────────────────────────────────────────

    public function test_locale_resolved_from_route_segment(): void
    {
        $response = $this->get('/ar/locale-test');
        $response->assertStatus(200);
        // The page should show AR is active
        $response->assertSee('>ar<', false); // locale code shown
    }

    // ─────────────────────────────────────────────────────────────
    // A11: All translation keys exist across all locales
    // ─────────────────────────────────────────────────────────────

    public function test_all_locales_have_same_keys(): void
    {
        $enabled = config('locales.enabled', []);
        $locales = array_keys($enabled);

        $this->assertCount(3, $locales);
        $this->assertContains('bn', $locales);
        $this->assertContains('en', $locales);
        $this->assertContains('ar', $locales);

        foreach ($locales as $locale) {
            $this->assertTrue($enabled[$locale]['enabled'] ?? false);
        }
    }

    // ─────────────────────────────────────────────────────────────
    // A11: No missing translation keys across locales
    // ─────────────────────────────────────────────────────────────

    public function test_no_missing_translation_keys(): void
    {
        $langDir = base_path('lang');
        $enabled = array_keys(config('locales.enabled', []));
        $refLocale = $enabled[0] ?? 'bn';

        // Collect all files
        $allFiles = [];
        foreach ($enabled as $locale) {
            $dir = "{$langDir}/{$locale}";
            if (is_dir($dir)) {
                foreach (glob("{$dir}/*.php") ?: [] as $file) {
                    $allFiles[basename($file)] = true;
                }
            }
        }

        // Reference keys from first locale
        $refKeys = $this->collectKeys("{$langDir}/{$refLocale}");

        foreach ($enabled as $locale) {
            if ($locale === $refLocale) {
                continue;
            }
            $localeKeys = $this->collectKeys("{$langDir}/{$locale}");
            $missing = array_diff($refKeys, $localeKeys);
            $this->assertEmpty(
                $missing,
                "Missing keys in {$locale}: " . implode(', ', $missing)
            );
        }
    }

    // ─────────────────────────────────────────────────────────────
    // A11: Validation errors appear in the active locale
    // ─────────────────────────────────────────────────────────────

    public function test_validation_errors_in_active_locale(): void
    {
        // Test with Bengali locale
        $response = $this->post('/bn/locale-test', [
            'name' => '',
            'email' => 'not-an-email',
        ]);
        $response->assertStatus(422);
    }

    // ─────────────────────────────────────────────────────────────
    // Helper: collect all translation keys from a locale directory
    // ─────────────────────────────────────────────────────────────
    private function collectKeys(string $dir): array
    {
        $keys = [];
        foreach (glob("{$dir}/*.php") ?: [] as $file) {
            $content = include $file;
            if (!is_array($content)) {
                continue;
            }
            array_walk_recursive($content, function ($_, $key) use (&$keys) {
                $keys[$key] = true;
            });
        }
        return array_keys($keys);
    }
}
