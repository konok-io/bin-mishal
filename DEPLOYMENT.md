# Bin Mishal Travel - Deployment Guide

## Requirements
- PHP 8.2+
- MySQL 8.0+
- Redis 7.0+
- Composer 2.0+
- Nginx/Apache

## Quick Start (Docker)

```bash
# Clone and setup
git clone https://github.com/konok-io/bin-mishal.git
cd bin-mishal

# Copy environment
cp .env.production .env

# Generate keys
php artisan key:generate
php artisan horizon:publish
php artisan vendor:publish --all

# Start Docker
docker-compose up -d

# Run migrations & seeders
docker exec -it binmishal-app php artisan migrate --seed

# Visit http://localhost:8000
```

## Manual Deployment

### 1. Server Setup
```bash
# Ubuntu 22.04
sudo apt update && sudo apt upgrade
sudo apt install nginx php8.2-fpm php8.2-cli php8.2-mysql \
  php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip \
  php8.2-redis php8.2-gd php8.2-bcmath mysql-server redis-server

# PHP extensions
sudo apt install php8.2-intl php8.2-xmlrpc php8.2-soap
```

### 2. Nginx Configuration
```nginx
server {
    listen 80;
    server_name binmishal.com;
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

    ssl_certificate /etc/letsencrypt/live/binmishal.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/binmishal.com/privkey.pem;
    include /etc/letsencrypt/options-ssl-nginx.conf;
}
```

### 3. Cron Jobs
```bash
# Edit crontab
crontab -e

# Add these lines
* * * * * cd /var/www/binmishal && php artisan schedule:run >> /dev/null 2>&1
* * * * * cd /var/www/binmishal && php artisan queue:work redis --sleep=3 --tries=3 >> /dev/null 2>&1
```

### 4. Queue Worker (Supervisor)
```bash
sudo apt install supervisor
sudo nano /etc/supervisor/conf.d/binmishal-worker.conf
```

```ini
[program:binmishal-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/binmishal/artisan queue:work redis --sleep=3 --tries=3
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/binmishal/storage/logs/worker.log
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start binmishal-worker:*
```

## Environment Variables

| Variable | Description |
|----------|-------------|
| `APP_KEY` | Laravel application key |
| `APP_DEBUG` | Debug mode (false in production) |
| `DB_*` | MySQL connection settings |
| `REDIS_*` | Redis connection settings |
| `MAIL_*` | SMTP settings |
| `TWILIO_*` | SMS credentials |
| `WHATSAPP_*` | WhatsApp Cloud API |
| `MOYASAR_*` | Payment gateway |
| `HYPERPAY_*` | Payment gateway |

## Post-Deployment

### 1. Clear Caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 2. Create Admin User
```bash
php artisan tinker
User::create([...]);
```

### 3. Setup SSL
```bash
sudo certbot --nginx -d binmishal.com -d www.binmishal.com
```

## Monitoring

### Health Check
```bash
curl https://binmishal.com/up
```

### Queue Monitoring
```bash
php artisan horizon
# Visit /horizon
```

### Scheduler
```bash
php artisan schedule:list
```

## Troubleshooting

### Logs
```bash
tail -f storage/logs/laravel.log
tail -f storage/logs/worker.log
tail -f storage/logs/horizon.log
```

### Clear Cache
```bash
php artisan optimize:clear
```

### Recompile Assets
```bash
npm install
npm run build
```
