# RTFM.guide Deployment Checklist

Quick reference for deployment requirements.

## Required Services

### ✅ Mandatory

| Service | Purpose | Default Port |
|---------|---------|--------------|
| **PHP 8.2+** | Application runtime | - |
| **Database** | MySQL 8.0+ / PostgreSQL 14+ | 3306 / 5432 |
| **Web Server** | Nginx or Apache | 80, 443 |
| **Node.js 18+** | Build assets | - |

### 🔶 Highly Recommended

| Service | Purpose | Default Port |
|---------|---------|--------------|
| **Redis** | Cache, Sessions, Queue | 6379 |
| **Meilisearch** | Search Engine | 7700 |
| **Supervisor** | Queue Worker Manager | - |

### ⭐ Optional

| Service | Purpose |
|---------|---------|
| **Email Service** | Password resets, notifications |
| **S3/Storage** | File uploads, avatars |
| **OAuth Providers** | GitHub/Google/GitLab login |
| **Torchlight** | Code syntax highlighting |

---

## Critical Environment Variables

### Must Configure

```bash
# Application
APP_NAME="RTFM.guide"
APP_ENV=production
APP_KEY=                    # Generate with: php artisan key:generate
APP_DEBUG=false
APP_URL=https://rtfm.guide

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=rtfm_production
DB_USERNAME=rtfm_user
DB_PASSWORD=                # Your secure password

# Mail (for password resets)
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS=noreply@rtfm.guide
```

### Recommended

```bash
# Cache & Sessions (use Redis for production)
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

# Search
SCOUT_DRIVER=meilisearch
MEILISEARCH_HOST=http://127.0.0.1:7700
MEILISEARCH_KEY=            # Your Meilisearch master key
```

### Optional

```bash
# File Storage (S3)
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_BUCKET=rtfm-guide-production

# OAuth
GITHUB_CLIENT_ID=
GITHUB_CLIENT_SECRET=

# Syntax Highlighting
TORCHLIGHT_TOKEN=
```

---

## Quick Deployment Steps

### 1. Server Setup
```bash
# Install PHP, Nginx, MySQL, Redis, Supervisor
# See DEPLOYMENT.md for full commands
```

### 2. Application
```bash
git clone <repo>
composer install --no-dev --optimize-autoloader
npm install && npm run build
cp .env.example .env
# Edit .env with your values
php artisan key:generate
php artisan migrate --force
php artisan storage:link
php artisan optimize
```

### 3. Configure Nginx
```bash
# Copy Nginx config from DEPLOYMENT.md
sudo nginx -t
sudo systemctl restart nginx
```

### 4. SSL Certificate
```bash
sudo certbot --nginx -d rtfm.guide -d www.rtfm.guide
```

### 5. Queue Workers
```bash
# Setup supervisor (see DEPLOYMENT.md)
sudo supervisorctl start rtfm-worker:*
```

### 6. Scheduler
```bash
# Add to crontab
* * * * * cd /var/www/rtfm && php artisan schedule:run >> /dev/null 2>&1
```

---

## Service URLs

| Service | URL | Purpose |
|---------|-----|---------|
| Application | https://rtfm.guide | Main site |
| Health Check | https://rtfm.guide/up | Monitor uptime |
| Meilisearch | http://localhost:7700 | Search admin (local only) |

---

## Firewall Ports

### Must Open

- `80` (HTTP - redirects to HTTPS)
- `443` (HTTPS)

### Internal Only

- `3306` (MySQL) - Do NOT expose publicly
- `5432` (PostgreSQL) - Do NOT expose publicly
- `6379` (Redis) - Do NOT expose publicly
- `7700` (Meilisearch) - Do NOT expose publicly

---

## Pre-Deployment Checklist

- [ ] Server meets minimum requirements (PHP 8.2+, 2GB RAM)
- [ ] Domain DNS points to server IP
- [ ] SSL certificate ready or Certbot installed
- [ ] Database created with user/password
- [ ] Redis installed and running
- [ ] Meilisearch installed and running
- [ ] Email service credentials obtained
- [ ] `.env` file configured
- [ ] File permissions set correctly

---

## Post-Deployment Verification

```bash
# Check services are running
sudo systemctl status php8.4-fpm
sudo systemctl status nginx
sudo systemctl status mysql
sudo systemctl status redis
sudo supervisorctl status rtfm-worker:*

# Test application
curl -I https://rtfm.guide
# Should return: HTTP/2 200

# Check logs
tail -f /var/www/rtfm/storage/logs/laravel.log
```

---

## Common Issues

| Problem | Solution |
|---------|----------|
| 500 Error | Check logs: `tail -f storage/logs/laravel.log` |
| Storage errors | Fix permissions: `sudo chmod -R 775 storage` |
| Search not working | Reindex: `php artisan scout:import "App\Models\Guide"` |
| Queue not processing | Restart workers: `sudo supervisorctl restart rtfm-worker:*` |
| Assets not loading | Rebuild: `npm run build` |

---

## Quick Commands

```bash
# Clear caches
php artisan optimize:clear

# Rebuild caches
php artisan optimize

# View logs
tail -f storage/logs/laravel.log

# Restart queue workers
sudo supervisorctl restart rtfm-worker:*

# Reindex search
php artisan scout:flush "App\Models\Guide"
php artisan scout:import "App\Models\Guide"
```

---

For detailed instructions, see **DEPLOYMENT.md**
