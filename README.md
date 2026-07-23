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
