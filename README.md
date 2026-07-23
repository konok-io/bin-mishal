# Bin Mishal Travels

A comprehensive travel management system built with Laravel 12 for Saudi Arabia travel agencies serving Bangladeshi expatriates.

## Features

- Multi-language Support: English, Arabic (RTL), Bengali
- Flight Booking: Request, quote, and ticket management
- Visa Processing: Complete visa application workflow
- Umrah Packages: Package booking and management
- Payment Integration: Moyasar, HyperPay payment gateways
- WhatsApp Integration: Meta Cloud API notifications
- Admin Dashboard: Complete CRM with analytics
- PWA Support: Offline-capable progressive web app

## Requirements

- PHP 8.2+
- MySQL 8.0+
- Redis 7.0+
- Composer 2.x
- Node.js 18+

## Quick Start

```bash
# Clone repository
git clone https://github.com/konok-io/bin-mishal.git
cd bin-mishal

# Install dependencies
composer install
npm install

# Environment setup
cp .env.production .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed

# Storage
php artisan storage:link

# Build assets
npm run build

# Start server
php artisan serve
```

## Docker

```bash
docker-compose up -d
docker exec -it binmishal-app php artisan migrate --seed
```

## Default Admin Credentials

- Email: admin@binmishal.com
- Password: password

## Documentation

- Installation Guide: ./INSTALL.md
- Deployment Guide: ./DEPLOYMENT.md

## Translation Manager

The platform includes a comprehensive Translation Manager for maintaining multilingual content.

### Running the Translation Sync

After any design update that adds new UI text:

```bash
# Sync translation keys from code to database
php artisan translations:sync

# Check for missing translations (used in CI/CD)
php artisan translations:sync --check
```

### Admin Panel

Access the Translation Manager at `/admin/filament/resources/translations`:

1. **View All Keys**: See all translation keys with their status
2. **Filter by Group**: Filter by translation group (app, navigation, home, etc.)
3. **Find Missing**: Filter by "Missing Translations" to find incomplete keys
4. **Recently Added**: Filter by "Recently Added" to find new keys from latest code
5. **Inline Editing**: Click any row to edit all three language values
6. **Bulk Export**: Select multiple keys and export to CSV
7. **Bulk Import**: Import translations from CSV for external translation

### Translation Status Meanings

- **Complete**: All three languages (Bengali, English, Arabic) are filled
- **Missing Bengali**: Bengali translation is empty
- **Missing English**: English translation is empty
- **Missing Arabic**: Arabic translation is empty
- **Needs Review**: No translations filled (new key)

### CI/CD Integration

The translation check runs automatically in CI:

```yaml
# GitHub Actions workflow (translations.yml)
- name: Check for Missing Translations
  run: php artisan translations:sync --check
```

If this check fails, new translation keys have been added to the code without providing translations in all three languages.

### Translation Hierarchy

1. Database translations (admin-managed) take priority over file translations
2. File translations in `lang/{locale}/*.php` are used as fallback
3. If a key is missing from both, the raw key is displayed (never empty)

## Tech Stack

- Backend: Laravel 12, PHP 8.2
- Frontend: Bootstrap 5, Livewire, Alpine.js
- Database: MySQL 8.0
- Cache: Redis
- Queue: Laravel Horizon
- Auth: Laravel Sanctum
- Payments: Moyasar, HyperPay
- Notifications: WhatsApp (Meta Cloud API), Email, SMS

## License

MIT License
