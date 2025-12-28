# RTFM.guide Deployment Guide

Complete deployment guide for RTFM.guide application.

## Table of Contents

1. [Infrastructure Requirements](#infrastructure-requirements)
2. [Required Services](#required-services)
3. [Environment Variables](#environment-variables)
4. [Deployment Steps](#deployment-steps)
5. [Post-Deployment](#post-deployment)
6. [Monitoring & Maintenance](#monitoring--maintenance)

---

## Infrastructure Requirements

### Minimum Server Requirements

- **PHP**: 8.2 or higher
- **Node.js**: 18.x or higher
- **Database**: MySQL 8.0+ / PostgreSQL 14+ / SQLite
- **Memory**: 2GB RAM minimum (4GB+ recommended for production)
- **Storage**: 10GB minimum
- **Web Server**: Nginx or Apache

### Recommended Stack

- **Server**: Ubuntu 22.04 LTS or similar
- **PHP**: 8.4 (current version in use)
- **Database**: MySQL 8.0 or PostgreSQL 15
- **Cache**: Redis 7.x
- **Queue**: Redis or Database
- **Search**: Meilisearch 1.x
- **Process Manager**: Supervisor (for queue workers)

---

## Required Services

### 1. Database

**MySQL 8.0+ (Recommended)**
```bash
# Connection details needed
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rtfm_production
DB_USERNAME=rtfm_user
DB_PASSWORD=secure_password_here
```

**PostgreSQL 14+**
```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=rtfm_production
DB_USERNAME=rtfm_user
DB_PASSWORD=secure_password_here
```

**SQLite (Development Only)**
```bash
DB_CONNECTION=sqlite
# No additional configuration needed
```

### 2. Meilisearch (Search Engine)

Required for guide search functionality.

**Installation**:
```bash
# Using Docker
docker run -d --name meilisearch \
  -p 7700:7700 \
  -v $(pwd)/meili_data:/meili_data \
  getmeili/meilisearch:latest

# Or install directly
curl -L https://install.meilisearch.com | sh
```

**Environment Variables**:
```bash
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=your_master_key_here
SCOUT_DRIVER=meilisearch
```

### 3. Redis (Optional but Recommended)

For caching, sessions, and queue processing.

**Installation**:
```bash
# Ubuntu/Debian
sudo apt install redis-server

# Using Docker
docker run -d --name redis -p 6379:6379 redis:7-alpine
```

**Environment Variables**:
```bash
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Use Redis for cache and sessions
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### 4. Email Service

Required for password resets, notifications, and user verification.

**Options**:

**a) SMTP (General)**
```bash
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@rtfm.guide
MAIL_FROM_NAME="${APP_NAME}"
```

**b) Mailgun**
```bash
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=mg.rtfm.guide
MAILGUN_SECRET=key-your_mailgun_secret
MAILGUN_ENDPOINT=api.mailgun.net
```

**c) Postmark**
```bash
MAIL_MAILER=postmark
POSTMARK_TOKEN=your_postmark_token
```

**d) Amazon SES**
```bash
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
```

### 5. File Storage (Optional)

For user avatars, guide attachments, etc.

**a) Local Storage (Default)**
```bash
FILESYSTEM_DISK=local
```

**b) Amazon S3**
```bash
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=rtfm-guide-production
AWS_USE_PATH_STYLE_ENDPOINT=false
```

### 6. OAuth Providers (Optional)

For social authentication with Laravel Socialite.

**GitHub**
```bash
GITHUB_CLIENT_ID=your_github_client_id
GITHUB_CLIENT_SECRET=your_github_client_secret
GITHUB_REDIRECT_URI=https://rtfm.guide/auth/github/callback
```

**Google**
```bash
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=https://rtfm.guide/auth/google/callback
```

**GitLab**
```bash
GITLAB_CLIENT_ID=your_gitlab_client_id
GITLAB_CLIENT_SECRET=your_gitlab_client_secret
GITLAB_REDIRECT_URI=https://rtfm.guide/auth/gitlab/callback
```

### 7. Code Highlighting (Torchlight)

For syntax highlighting in guides.

```bash
TORCHLIGHT_TOKEN=your_torchlight_token
```

Get your token from: https://torchlight.dev

### 8. Monitoring (Optional)

**Laravel Nightwatch** (Already included)
- Monitors application performance
- No additional setup required if using Laravel Vapor

---

## Environment Variables

### Complete `.env` Configuration

```bash
# ============================================================================
# APPLICATION
# ============================================================================
APP_NAME="RTFM.guide"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://rtfm.guide

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
BCRYPT_ROUNDS=12

# ============================================================================
# LOGGING
# ============================================================================
LOG_CHANNEL=stack
LOG_STACK=daily
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

# ============================================================================
# DATABASE
# ============================================================================
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rtfm_production
DB_USERNAME=rtfm_user
DB_PASSWORD=secure_password_here

# ============================================================================
# CACHE & SESSION
# ============================================================================
CACHE_STORE=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.rtfm.guide

# ============================================================================
# QUEUE
# ============================================================================
QUEUE_CONNECTION=redis

# ============================================================================
# REDIS
# ============================================================================
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# ============================================================================
# SEARCH (Meilisearch)
# ============================================================================
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=your_master_key_here

# ============================================================================
# MAIL
# ============================================================================
MAIL_MAILER=smtp
MAIL_HOST=smtp.yourmailservice.com
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@rtfm.guide
MAIL_FROM_NAME="${APP_NAME}"

# ============================================================================
# FILE STORAGE (AWS S3)
# ============================================================================
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=rtfm-guide-production
AWS_USE_PATH_STYLE_ENDPOINT=false

# ============================================================================
# OAUTH PROVIDERS (Optional - for social login)
# ============================================================================
# GitHub
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=
GITHUB_REDIRECT_URI="${APP_URL}/auth/github/callback"

# Google
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

# GitLab
GITLAB_CLIENT_ID=
GITLAB_CLIENT_SECRET=
GITLAB_REDIRECT_URI="${APP_URL}/auth/gitlab/callback"

# ============================================================================
# SYNTAX HIGHLIGHTING
# ============================================================================
TORCHLIGHT_TOKEN=your_torchlight_token

# ============================================================================
# BROADCAST (Future use)
# ============================================================================
BROADCAST_CONNECTION=log

# ============================================================================
# VITE (Build process)
# ============================================================================
VITE_APP_NAME="${APP_NAME}"
```

---

## Deployment Steps

### 1. Server Preparation

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y \
  php8.4-fpm \
  php8.4-cli \
  php8.4-mysql \
  php8.4-pgsql \
  php8.4-sqlite3 \
  php8.4-redis \
  php8.4-mbstring \
  php8.4-xml \
  php8.4-bcmath \
  php8.4-curl \
  php8.4-zip \
  php8.4-gd \
  nginx \
  mysql-server \
  redis-server \
  supervisor \
  git \
  unzip

# Install Node.js 18+
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
sudo mv composer.phar /usr/local/bin/composer
php -r "unlink('composer-setup.php');"
```

### 2. Application Setup

```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/yourusername/rtfm.git
cd rtfm

# Set proper permissions
sudo chown -R www-data:www-data /var/www/rtfm
sudo chmod -R 755 /var/www/rtfm
sudo chmod -R 775 /var/www/rtfm/storage
sudo chmod -R 775 /var/www/rtfm/bootstrap/cache

# Copy environment file
cp .env.example .env

# Edit .env with your production values
nano .env

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Seed initial data (optional)
php artisan db:seed --force

# Create storage link
php artisan storage:link

# Optimize application
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Index guides for search
php artisan scout:import "App\Models\Guide"
```

### 3. Nginx Configuration

Create `/etc/nginx/sites-available/rtfm.guide`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name rtfm.guide www.rtfm.guide;

    # Redirect to HTTPS
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name rtfm.guide www.rtfm.guide;

    root /var/www/rtfm/public;
    index index.php index.html;

    # SSL Configuration (use Certbot to generate)
    ssl_certificate /etc/letsencrypt/live/rtfm.guide/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/rtfm.guide/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;
    ssl_prefer_server_ciphers on;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    # Gzip
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/json application/javascript;

    # Logs
    access_log /var/log/nginx/rtfm.guide-access.log;
    error_log /var/log/nginx/rtfm.guide-error.log;

    # Root location
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP-FPM
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Deny access to sensitive files
    location ~ /\. {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable the site:
```bash
sudo ln -s /etc/nginx/sites-available/rtfm.guide /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### 4. SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Generate certificate
sudo certbot --nginx -d rtfm.guide -d www.rtfm.guide

# Auto-renewal is set up automatically
# Test renewal
sudo certbot renew --dry-run
```

### 5. Queue Worker Setup

Create `/etc/supervisor/conf.d/rtfm-worker.conf`:

```ini
[program:rtfm-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/rtfm/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/rtfm/storage/logs/worker.log
stopwaitsecs=3600
```

Start supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start rtfm-worker:*
```

### 6. Scheduler Setup

Add to crontab:
```bash
sudo crontab -e -u www-data
```

Add this line:
```cron
* * * * * cd /var/www/rtfm && php artisan schedule:run >> /dev/null 2>&1
```

---

## Post-Deployment

### 1. Verify Services

```bash
# Check PHP-FPM
sudo systemctl status php8.4-fpm

# Check Nginx
sudo systemctl status nginx

# Check MySQL/PostgreSQL
sudo systemctl status mysql
# or
sudo systemctl status postgresql

# Check Redis
sudo systemctl status redis

# Check Meilisearch
curl http://localhost:7700/health

# Check Queue Workers
sudo supervisorctl status rtfm-worker:*
```

### 2. Test Application

```bash
# Visit your domain
curl -I https://rtfm.guide

# Check logs for errors
tail -f /var/www/rtfm/storage/logs/laravel.log
tail -f /var/log/nginx/rtfm.guide-error.log
```

### 3. Initial Data

```bash
# Run seeders if needed
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=RtfmMessageSeeder

# Create admin user (if you have a command for this)
php artisan user:create admin@rtfm.guide --admin
```

### 4. Performance Optimization

```bash
# Enable OPcache (edit /etc/php/8.4/fpm/php.ini)
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0

# Restart PHP-FPM after changes
sudo systemctl restart php8.4-fpm
```

---

## Monitoring & Maintenance

### Health Checks

The application has a built-in health endpoint: `https://rtfm.guide/up`

### Log Management

```bash
# View application logs
tail -f /var/www/rtfm/storage/logs/laravel.log

# View Nginx access logs
tail -f /var/log/nginx/rtfm.guide-access.log

# View Nginx error logs
tail -f /var/log/nginx/rtfm.guide-error.log

# View queue worker logs
tail -f /var/www/rtfm/storage/logs/worker.log
```

### Regular Maintenance

```bash
# Clear old logs (weekly)
php artisan log:clear --days=30

# Optimize application (after updates)
php artisan optimize:clear
php artisan optimize

# Reindex search (as needed)
php artisan scout:flush "App\Models\Guide"
php artisan scout:import "App\Models\Guide"
```

### Backups

**Database Backup**
```bash
# MySQL
mysqldump -u rtfm_user -p rtfm_production > backup_$(date +%Y%m%d).sql

# PostgreSQL
pg_dump -U rtfm_user rtfm_production > backup_$(date +%Y%m%d).sql
```

**Application Backup**
```bash
# Backup storage directory
tar -czf storage_backup_$(date +%Y%m%d).tar.gz /var/www/rtfm/storage

# Backup .env
cp /var/www/rtfm/.env /var/www/rtfm/.env.backup_$(date +%Y%m%d)
```

### Updates

```bash
# Pull latest code
cd /var/www/rtfm
git pull origin main

# Update dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear and rebuild caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Restart services
sudo supervisorctl restart rtfm-worker:*
sudo systemctl restart php8.4-fpm
```

---

## Troubleshooting

### Common Issues

**Storage permissions error**
```bash
sudo chown -R www-data:www-data /var/www/rtfm/storage
sudo chmod -R 775 /var/www/rtfm/storage
```

**Queue not processing**
```bash
sudo supervisorctl restart rtfm-worker:*
tail -f /var/www/rtfm/storage/logs/worker.log
```

**Search not working**
```bash
# Check Meilisearch is running
curl http://localhost:7700/health

# Reindex
php artisan scout:flush "App\Models\Guide"
php artisan scout:import "App\Models\Guide"
```

**500 Error**
```bash
# Check logs
tail -f /var/www/rtfm/storage/logs/laravel.log

# Clear caches
php artisan optimize:clear

# Check permissions
sudo chown -R www-data:www-data /var/www/rtfm
```

---

## Security Checklist

- [ ] `APP_DEBUG=false` in production
- [ ] `APP_ENV=production`
- [ ] Strong `APP_KEY` generated
- [ ] Database credentials are secure
- [ ] SSL certificate installed and auto-renewing
- [ ] Firewall configured (UFW or similar)
- [ ] SSH key-based authentication only
- [ ] Regular backups configured
- [ ] Security headers configured in Nginx
- [ ] File upload validation in place
- [ ] Rate limiting configured
- [ ] CSRF protection enabled (default)
- [ ] XSS protection enabled (default)

---

## Performance Checklist

- [ ] OPcache enabled
- [ ] Redis configured for cache and sessions
- [ ] Queue workers running
- [ ] Gzip compression enabled
- [ ] Static assets cached
- [ ] Database queries optimized
- [ ] Search indexed
- [ ] CDN configured (optional)

---

## Scaling Considerations

### Horizontal Scaling

- Use load balancer (Nginx, HAProxy)
- Shared Redis instance for cache/sessions
- Centralized database
- S3 for file storage (not local)
- Multiple queue workers

### Vertical Scaling

- Increase server resources
- Optimize database queries
- Add database read replicas
- Implement caching strategies

---

## Support & Documentation

- **Laravel**: https://laravel.com/docs
- **Inertia.js**: https://inertiajs.com
- **Meilisearch**: https://www.meilisearch.com/docs
- **Fortify**: https://laravel.com/docs/fortify
- **Tailwind CSS**: https://tailwindcss.com/docs
