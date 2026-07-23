# STEP 0 — CMS FOUNDATION INSPECTION REPORT

## 1. EXISTING MIGRATIONS (All Tables & Columns)

| Table | Columns |
|-------|---------|
| `users` | id, name, name_bn, name_ar, email, phone, whatsapp, password, user_type, nationality, passport_no, iqama_no, iqama_expiry, city, address, preferred_language, avatar, status, email_verified_at, phone_verified_at, otp_code, otp_expires_at, referred_by, referral_code, last_login_at, last_login_ip, timestamps, softDeletes |
| `customers` | id, user_id FK, customer_code, company_name, sponsor_name, sponsor_id_no, profession, work_city, monthly_income, source, assigned_to FK, lifetime_value, total_bookings, notes, tags JSON, timestamps, softDeletes |
| `employees` | id, user_id FK, employee_code, department, designation, salary, joining_date, status, timestamps, softDeletes |
| `branches` | id, name, name_bn, name_ar, code, city, address, phone, email, whatsapp, latitude, longitude, is_main, status, timestamps |
| `settings` | id, key UNIQUE, value, group, type, label, description, timestamps |
| `leads` | id, name, email, phone, whatsapp, source, status, notes, assigned_to FK, converted_at, timestamps, softDeletes |
| `lead_activities` | id, lead_id FK, type, description, timestamps |
| `airlines` | id, name, name_bn, name_ar, code, logo, active, timestamps |
| `airports` | id, name, name_bn, name_ar, code, city, country, type, timestamps |
| `flight_requests` | id, customer_id FK, trip_type, from_city, to_city, departure_date, return_date, adults, children, infants, cabin_class, special_requests, status, timestamps |
| `flight_quotes` | id, flight_request_id FK, airline_id FK, price, taxes, total_price, notes, expires_at, timestamps |
| `bookings` | id, booking_number, customer_id FK, type, total_amount, vat_amount, discount_amount, final_amount, currency, status, issued_by FK, booked_at, issued_at, notes, timestamps, softDeletes |
| `passengers` | id, booking_id FK, first_name, last_name, gender, date_of_birth, passport_no, passport_expiry, nationality, type (adult/child/infant), timestamps |
| `visa_types` | id, name, name_bn, name_ar, slug, country, category, description, description_bn, description_ar, processing_days, government_fee, service_fee, total_fee, required_documents JSON, eligibility_criteria JSON, icon, is_featured, status, timestamps |
| `visa_applications` | id, visa_type_id FK, customer_id FK, application_number, full_name, passport_no, nationality, date_of_birth, gender, email, phone, processing_status, status, applied_at, processed_at, delivered_at, notes, timestamps, softDeletes |
| `visa_application_documents` | id, visa_application_id FK, document_type, file_path, uploaded_at, verified, timestamps |
| `visa_status_logs` | id, visa_application_id FK, status, notes, created_by FK, timestamps |
| `umrah_packages` | id, title, title_bn, title_ar, slug, description, duration_days, duration_nights, makkah_hotel, makkah_hotel_stars, makkah_distance_meters, makkah_nights, madinah_hotel, madinah_hotel_stars, madinah_distance_meters, madinah_nights, transport_type, meal_plan, inclusions JSON, exclusions JSON, itinerary JSON, price_quad/triple/double/single, child_price, infant_price, currency, departure_dates JSON, available_seats, booked_seats, featured_image, gallery JSON, is_featured, status, timestamps |
| `invoices` | id, invoice_number, booking_id FK, customer_id FK, subtotal, vat_rate, vat_amount, discount_amount, total, currency, status, due_date, paid_at, notes, timestamps |
| `invoice_items` | id, invoice_id FK, description, quantity, unit_price, total, timestamps |
| `payments` | id, payment_number, invoice_id FK, customer_id FK, amount, method, transaction_id, status, paid_at, idempotency_key, timestamps |
| `documents` | id, user_id FK, documentable_type, documentable_id, type, file_path, file_name, file_size, mime_type, expires_at, verified, verified_by FK, notes, timestamps |
| `appointment_slots` | id, branch_id FK, date, start_time, end_time, capacity, booked_count, is_available, timestamps |
| `appointments` | id, appointment_number, branch_id FK, customer_id FK, service_type, appointment_date, start_time, end_time, status, notes, created_by FK, timestamps, softDeletes |
| `notifications` | id, type, notifiable_type, notifiable_id, data JSON, read_at, timestamps |
| `activity_log` | id, log_name, description, subject_type, subject_id, causer_type, causer_id, properties JSON, created_at |

---

## 2. EXISTING MODELS ($translatable / $fillable / $casts)

### User Model
```php
$fillable = ['name', 'name_bn', 'name_ar', 'email', 'phone', 'whatsapp', 'password', 'user_type', ...];
$translatable = []; // Not using HasTranslations
$casts = ['email_verified_at' => 'datetime', 'user_type' => UserType::class, 'status' => UserStatus::class, 'preferred_language' => Language::class];
Traits: HasFactory, HasRoles, InteractsWithMedia, LogsActivity, Notifiable, SoftDeletes
```

### UmrahPackage Model
```php
$fillable = ['title', 'title_bn', 'title_ar', 'slug', 'description', 'duration_days', ...];
$translatable = ['title', 'title_bn', 'title_ar', 'description', 'makkah_hotel', 'madinah_hotel'];
$casts = ['inclusions' => 'array', 'gallery' => 'array', 'is_featured' => 'boolean'];
```

### VisaType Model
```php
$fillable = ['name', 'name_bn', 'name_ar', 'slug', 'country', 'category', 'description', ...];
$translatable = ['name', 'name_bn', 'name_ar', 'description', 'description_bn', 'description_ar'];
$casts = ['required_documents' => 'array', 'is_featured' => 'boolean'];
```

### Branch Model
```php
$fillable = ['name', 'name_bn', 'name_ar', 'code', 'city', 'address', 'phone', ...];
$translatable = ['name', 'name_ar', 'address'];
$casts = ['latitude' => 'decimal:8', 'longitude' => 'decimal:8', 'is_main' => 'boolean'];
```

### Setting Model
```php
$fillable = ['key', 'value', 'group', 'type', 'label', 'description'];
$casts = ['value' => 'string'];
Methods: getValue(), setValue(), allSettings(), defaults()
```

---

## 3. EXISTING FILAMENT RESOURCES

**NONE.** The project currently uses plain Blade views with Bootstrap 5 for admin pages. Filament needs to be installed.

---

## 4. EXISTING SETTINGS TABLE STRUCTURE

**Table**: `settings`
- `id` (PK)
- `key` (string, UNIQUE)
- `value` (text, nullable)
- `group` (string, default: 'general')
- `type` (string, default: 'text') — values: 'text', 'number', 'boolean', 'json'
- `label` (string, nullable)
- `description` (text, nullable)
- `timestamps`

**How Setting values are read:**
```php
Setting::getValue(string $key, $default = null)  // Reads from DB, falls back to defaults(), auto-casts by type
Setting::setValue(string $key, $value): void     // Uses updateOrCreate, forgets cache
Setting::allSettings(): array                     // Cached, merges DB values with defaults
```

---

## 5. EXISTING ROUTES/web.php STRUCTURE & LOCALE MIDDLEWARE

**Locale Middleware** (`SetLocale.php`):
- Priority: Route segment > Session > Cookie > User preference > Accept-Language header > Config default
- Sets `App::setLocale()`, shares `localeConfig`, `currentLocale`, `enabledLocales` to views
- Sets 1-year locale cookie

**Route Structure:**
```php
// Root redirect
Route::get('/', fn() => redirect('/' . config('app.locale')))->name('root');

// Admin (outside locale, English only)
Route::prefix('admin')->middleware(['auth', 'role:admin,super_admin'])->group(function () {
    // All admin resource routes
});

// Locale-prefixed public routes
Route::prefix('{locale}')
    ->where(['locale' => 'bn|en|ar'])
    ->middleware(['web', 'setlocale'])
    ->group(function () {
        Route::get('/', fn() => view('welcome'))->name('home');
        // NOTE: No catch-all for CMS pages yet
    });
```

---

## 6. EXISTING BLADE LAYOUTS & COMPONENTS

| File | Purpose |
|------|---------|
| `layouts/public.blade.php` | Minimal public layout with slots for header/footer/content |
| `layouts/admin.blade.php` | Bootstrap 5 admin layout |
| `layouts/portal.blade.php` | Customer portal layout |
| `welcome.blade.php` | Static homepage with hardcoded menu, hero, services, stats, footer |
| `components/language-switcher.blade.php` | Language switcher component |

**Current hardcoded content in welcome.blade.php:**
- Header with logo + Login/Register links
- Hero section with hardcoded text
- Services grid (4 cards: Umrah, Visa, Flights, Hotels)
- Stats section (10+ years, 5000+ customers, 24/7 support)
- Footer with copyright

---

## 7. INSTALLED SPATIE PACKAGES & VERSIONS

| Package | Version | Purpose |
|---------|---------|---------|
| `spatie/laravel-permission` | ^6.10 | Role/Permission management |
| `spatie/laravel-medialibrary` | ^11.0 | File/media management |
| `spatie/laravel-translatable` | ^6.9 | Multilingual content |
| `spatie/laravel-activitylog` | ^4.8 | Activity logging |
| `spatie/laravel-sitemap` | ^7.2 | Sitemap generation |

---

## 8. TRANSLATION STRUCTURE (spatie/laravel-translatable)

**Pattern Used**: Column-per-locale (NOT JSON columns)
- Single field: `name`
- Bengali: `name_bn`
- Arabic: `name_ar`

**Locales Enabled**: bn, en, ar (from config/locales.php)

**RTL Support**: Arabic (ar) has `direction: 'rtl'`

---

## 9. CSS/Tailwind Setup

- **Tailwind Version**: 4.x (not 3.4 as mentioned)
- **Fonts**: Hind Siliguri (bn), IBM Plex Sans Arabic (ar), Inter (en)
- **Build**: Vite with `@theme` brand tokens
- **Note**: Brand colors are currently hardcoded in welcome.blade.php style block

---

## MIGRATIONS NEEDED FOR CMS

Based on the inspection, the following migrations need to be created:

1. **`create_menus_table`** — Menu locations
2. **`create_menu_items_table`** — Nested menu items with translatable fields
3. **`create_pages_table`** — CMS pages with multilingual slugs
4. **`create_page_sections_table`** — Page sections with content/settings/data_source JSON
5. **`create_page_section_items_table`** — Repeatable items within sections
6. **`create_seo_redirects_table`** — 301 redirect table

---

## KEY OBSERVATIONS FOR BUILD

1. **Filament NOT installed** — Must add to composer.json
2. **Laravel 13** (not 12) — Adjust any version-specific code
3. **Translatable pattern**: Column-per-locale (not JSON) — Match existing pattern
4. **Existing Setting model** — Extend it rather than replace
5. **No existing page resources** — All public pages need to be CMS-driven
6. **Static welcome.blade.php** — Will be replaced by dynamic PageController
7. **No existing news/blog models** — May need to create or use existing entities
8. **Existing spatie packages** — Can leverage for media/translatable

---

*Report generated for CMS Foundation Build — Bin Mishal Saudi Expat Super Platform*
