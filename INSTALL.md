# বিন মিশাল ট্রাভেল - ইনস্টলেশন গাইড
# Bin Mishal Travel - Complete Installation Guide

## 📋 Prerequisites / প্রয়োজনীয়তা

- PHP 8.2+
- Composer 2.x
- MySQL 8.0+ / SQLite
- Node.js 18+
- Redis (for queues)
- Git

---

## 🚀 Quick Start (দ্রুত শুরু)

### Step 1: Clone Repository
```bash
git clone https://github.com/konok-io/bin-mishal.git
cd bin-mishal
```

### Step 2: Install Dependencies
```bash
composer install
npm install
```

### Step 3: Environment Setup
```bash
cp .env.production .env
php artisan key:generate
```

### Step 4: Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### Step 5: Storage & Permissions
```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Step 6: Build Assets
```bash
npm run build
```

### Step 7: Start Server
```bash
php artisan serve
# অথবা production এ:
php artisan optimize
```

**🌐 Visit:** http://localhost:8000

---

## 🐳 Docker Setup (ডকার)

```bash
# Build and start
docker-compose up -d

# Run migrations
docker exec -it binmishal-app php artisan migrate

# Seed database
docker exec -it binmishal-app php artisan db:seed

# View logs
docker-compose logs -f app
```

---

## ⚙️ Manual Server Setup (ম্যানুয়াল সার্ভার)

### Ubuntu 22.04

```bash
# System Update
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2
sudo apt install php8.2-fpm php8.2-cli php8.2-mysql \
  php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip \
  php8.2-redis php8.2-gd php8.2-bcmath php8.2-intl

# Install Additional PHP Extensions
sudo apt install php8.2-sqlite3 php8.2-dom

# Install MySQL
sudo apt install mysql-server
sudo mysql_secure_installation

# Install Redis
sudo apt install redis-server
sudo systemctl enable redis-server

# Install Nginx
sudo apt install nginx

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### Create Database
```bash
sudo mysql -u root -p
```
```sql
CREATE DATABASE binmishal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'binmishal_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON binmishal.* TO 'binmishal_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### Nginx Configuration
```bash
sudo nano /etc/nginx/sites-available/binmishal
```
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/binmishal/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
```bash
sudo ln -s /etc/nginx/sites-available/binmishal /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

---

## 📋 All Commands (সকল কমান্ড)

### Installation
```bash
# 1. Clone
git clone https://github.com/konok-io/bin-mishal.git
cd bin-mishal

# 2. Install PHP packages
composer install

# 3. Install Node packages
npm install

# 4. Environment file
cp .env.production .env

# 5. Generate app key
php artisan key:generate

# 6. Generate JWT secret (if using Sanctum)
php artisan sanctum:secret

# 7. Run migrations
php artisan migrate

# 8. Seed database (creates admin user)
php artisan db:seed

# 9. Link storage
php artisan storage:link

# 10. Build assets
npm run build

# 11. Clear cache
php artisan optimize:clear
```

### Development
```bash
# Start dev server
php artisan serve

# Hot reload (Vite)
npm run dev

# Build production assets
npm run build

# Watch assets
npm run watch
```

### Queue Workers
```bash
# Start queue worker
php artisan queue:work redis --sleep=3 --tries=3

# Start Horizon (recommended)
php artisan horizon

# Production queue with supervisor
supervisorctl start binmishal-worker:*
```

### Scheduler (Cron)
```bash
# Add to crontab
crontab -e
```
```
* * * * * cd /path/to/binmishal && php artisan schedule:run >> /dev/null 2>&1
```

### Database Commands
```bash
# Fresh install with seed
php artisan migrate:fresh --seed

# Seed only
php artisan db:seed

# Create seeder
php artisan make:seeder MySeeder

# Rollback
php artisan migrate:rollback

# Reset all
php artisan migrate:reset
```

### Cache & Optimization
```bash
# Clear all caches
php artisan optimize:clear

# Cache routes
php artisan route:cache
php artisan route:clear

# Cache config
php artisan config:cache
php artisan config:clear

# Cache views
php artisan view:cache
php artisan view:clear

# Production optimization
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### File Management
```bash
# Link storage
php artisan storage:link

# Clear media cache
php artisan media:clear

# Publish media
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider"
```

### Testing
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=BookingTest

# Run with coverage
php artisan test --coverage

# Run PHPUnit directly
./vendor/bin/phpunit
```

### Artisan Commands
```bash
# List all commands
php artisan list

# Help for command
php artisan help migrate

# Create controller
php artisan make:controller Admin/DashboardController

# Create model with migration
php artisan make:model Booking -m

# Create policy
php artisan make:policy BookingPolicy --model=Booking

# Create job
php artisan make:job SendNotification

# Create notification
php artisan make:notification InvoicePaid
```

---

## 🔐 Default Admin Credentials

After seeding:
- **Email:** admin@binmishal.com
- **Password:** password (change immediately!)

---

## 📧 Environment Variables (.env)

```env
APP_NAME="Bin Mishal Travel"
APP_ENV=local
APP_KEY=  # Run: php artisan key:generate
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=binmishal
DB_USERNAME=binmishal_user
DB_PASSWORD=  # Your password

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=noreply@binmishal.com

WHATSAPP_ENABLED=false
WHATSAPP_PHONE_NUMBER_ID=
WHATSAPP_ACCESS_TOKEN=
WHATSAPP_APP_SECRET=
WHATSAPP_VERIFY_TOKEN=

MOYASAR_SECRET_KEY=
MOYASAR_PUBLISHABLE_KEY=
MOYASAR_ENVIRONMENT=test
```

---

## 🚀 Deployment Checklist

```bash
# 1. Set environment
APP_ENV=production
APP_DEBUG=false

# 2. Clear caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data .

# 4. Queue workers (use Supervisor in production)
php artisan queue:restart

# 5. Scheduler
# Add cron: * * * * * cd /path && php artisan schedule:run
```

---

## 🆘 Troubleshooting

### Permission Error
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data .
```

### Class Not Found
```bash
composer dump-autoload
php artisan optimize:clear
```

### Database Error
```bash
php artisan migrate:fresh --seed
```

### Queue Not Working
```bash
php artisan queue:restart
php artisan horizon:publish
```

### Storage Error
```bash
php artisan storage:link
chmod -R 775 storage
```

---

## 📞 Support

- **GitHub Issues:** https://github.com/konok-io/bin-mishal/issues
- **Email:** support@binmishal.com
