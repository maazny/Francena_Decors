# Fancy Decorators CMS – Production Deployment Guide

This guide details the step-by-step instructions to deploy the Fancy Decorators Laravel 12 application to a production server environment.

---

## 1. System Requirements

Ensure the target server meets the following requirements:
* **PHP**: `^8.3` (with extensions: `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `curl`, `zip`, `sqlite3`, `gd`).
* **Web Server**: Nginx (recommended) or Apache.
* **Database**: SQLite (built-in) or MySQL/PostgreSQL (configured in `.env`).
* **Node.js**: `^20.0` (for asset compilation).

---

## 2. Server Directory Permissions

Ensure that the web server user (usually `www-data` or `nginx`) owns the storage and bootstrap cache directories:

```bash
sudo chown -R www-data:www-data /var/www/fancy-decorators
sudo chmod -R 775 /var/www/fancy-decorators/storage
sudo chmod -R 775 /var/www/fancy-decorators/bootstrap/cache
```

---

## 3. Production Environment Checklist (.env)

Make sure the following variables are configured for production:

```env
APP_NAME="Fancy Decorators"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=sqlite
DB_DATABASE=/var/www/fancy-decorators/database/database.sqlite

# Cache & Sessions
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database

# Mail Configuration (SMTP example)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@your-domain.com
MAIL_PASSWORD=your-secure-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="info@your-domain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 4. Deployment Steps

Run the following commands on the server:

### Step 4.1: Install Composer Dependencies
```bash
composer install --no-dev --optimize-autoloader
```

### Step 4.2: Install NPM Dependencies & Build Assets
```bash
npm ci
npm run build
```

### Step 4.3: Initialize Storage Link
```bash
php artisan storage:link
```

### Step 4.4: Database Migrations
```bash
php artisan migrate --force
```

### Step 4.5: Warm Production Caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

---

## 5. Queue Workers (Supervisor Configuration)

To run the email queue and database schedules in the background, create a Supervisor configuration file at `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/fancy-decorators/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/fancy-decorators/storage/logs/worker.log
stopwaitsecs=3600
```

Enable and start the worker process:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

---

## 6. Nginx Server Block Setup

Use the following Nginx block configuration to serve the application securely:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name your-domain.com;
    root /var/www/fancy-decorators/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";
    add_header Referrer-Policy "strict-origin-when-cross-origin";
    add_header Content-Security-Policy "upgrade-insecure-requests";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
