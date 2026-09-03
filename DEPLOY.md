# 🚀 YUHLEZ - Deployment Guide

Platform manajemen magang YUHLEZ - Laravel 12

---

## 📋 Table of Contents

1. [Local Development](#local-development)
2. [VPS Production](#vps-production)
3. [Default Accounts](#default-accounts)
4. [Architecture](#architecture)
5. [Troubleshooting](#troubleshooting)
6. [Backup](#backup)

---

## 🖥️ Local Development

### Prerequisites

- PHP 8.2+ (atau gunakan Docker)
- Composer
- Node.js 18+
- MySQL/MariaDB (atau gunakan Docker)

### Cara Cepat (Tanpa Docker)

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup database (pastikan MySQL sudah jalan)
cp .env.example .env
php artisan key:generate

# 3. Edit .env - isi DB credentials local Anda
# DB_HOST=127.0.0.1
# DB_DATABASE=yuhlez
# DB_USERNAME=root
# DB_PASSWORD=

# 4. Jalankan migrasi + seed
php artisan migrate --seed

# 5. Build assets + jalankan dev server
npm run dev        # terminal 1 (Vite HMR)
php artisan serve  # terminal 2 (http://127.0.0.1:8000)
```

### Cara dengan Docker (MySQL saja)

```bash
# 1. Jalankan MySQL via Docker
docker compose up -d mysql

# 2. Setup Laravel
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install && npm run build

# 3. Jalankan dev server
php artisan serve
```

### Cara Full Stack Docker

```bash
# Jalankan semua service (MySQL + App + Nginx + Queue)
docker compose --profile full up -d --build

# Akses: http://localhost:8080
# Stop: docker compose --profile full down
```

### Setup Google OAuth (untuk login Google)

1. Buka https://console.cloud.google.com
2. Buat Project baru
3. **APIs & Services → Credentials → Create OAuth 2.0 Client ID**
4. Application type: **Web Application**
5. Authorized redirect URIs:
   ```
   http://127.0.0.1:8000/v1/auth/google/callback
   ```
6. Copy Client ID dan Client Secret ke `.env`:
   ```
   GOOGLE_CLIENT_ID=xxxxx
   GOOGLE_CLIENT_SECRET=xxxxx
   ```

---

## 🌐 VPS Production

### Prerequisites

- VPS Ubuntu 22.04/24.04 (min. 2GB RAM, 1 vCPU)
- Domain pointed ke IP VPS
- Docker & Docker Compose terinstall
- Port 80 & 443 terbuka

### Step 1: Install Docker di VPS

```bash
# Install Docker
curl -fsSL https://get.docker.com -o get-docker.sh
sh get-docker.sh

# Install Docker Compose plugin
apt install docker-compose-plugin -y

# Verifikasi
docker --version
docker compose version
```

### Step 2: Clone/Copy Project

```bash
mkdir -p /var/www/yuhlez
cd /var/www/yuhlez

# Opsi A: dari Git
git clone https://github.com/your-repo/yuhlez-laravel.git .

# Opsi B: dari local (rsync)
rsync -avz --exclude='.env' --exclude='node_modules' --exclude='vendor' \
  ./ user@VPS_IP:/var/www/yuhlez/
```

### Step 3: Setup Environment

```bash
cp .env.example .env
nano .env   # Edit semua nilai yang perlu diganti
```

**Yang WAJIB diganti di .env untuk VPS:**

| Variable | Contoh |
|----------|--------|
| APP_ENV | production |
| APP_DEBUG | false |
| APP_URL | https://yourdomain.com |
| DB_PASSWORD | password_yang_kuat |
| GOOGLE_CLIENT_ID | dari Google Console |
| GOOGLE_CLIENT_SECRET | dari Google Console |
| MAIL_MAILER | smtp |
| MAIL_HOST | smtp.gmail.com |
| MAIL_USERNAME | email@gmail.com |
| MAIL_PASSWORD | xxxx-xxxx-xxxx-xxxx |

### Step 4: Build & Deploy

```bash
# Build images
docker compose -f docker-compose.prod.yml up -d --build

# Setup Laravel
docker compose -f docker-compose.prod.yml exec app php artisan key:generate
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker compose -f docker-compose.prod.yml exec app php artisan db:seed --force
docker compose -f docker-compose.prod.yml exec app php artisan storage:link
docker compose -f docker-compose.prod.yml exec app php artisan config:cache
docker compose -f docker-compose.prod.yml exec app php artisan route:cache
docker compose -f docker-compose.prod.yml exec app php artisan view:cache
```

### Step 5: Setup SSL (Let's Encrypt)

```bash
# Install certbot di VPS host (bukan di Docker)
apt install certbot -y

# Dapatkan SSL certificate
certbot certonly --standalone -d yourdomain.com -d www.yourdomain.com

# Copy ke Docker volume
docker compose -f docker-compose.prod.yml cp /etc/letsencrypt/live/yourdomain.com/fullchain.pem ssl_data:/fullchain.pem
docker compose -f docker-compose.prod.yml cp /etc/letsencrypt/live/yourdomain.com/privkey.pem ssl_data:/privkey.pem

# Reload nginx
docker compose -f docker-compose.prod.yml exec nginx nginx -s reload

# Auto-renewal (cron di VPS host)
(crontab -l 2>/dev/null; echo "0 3 * * * certbot renew --quiet") | crontab -
```

### Step 6: Google OAuth untuk Production

Di Google Console, tambahkan redirect URI production:
```
https://yourdomain.com/v1/auth/google/callback
```

### Step 7: Verifikasi

```bash
# Cek semua container berjalan
docker compose -f docker-compose.prod.yml ps

# Cek log
docker compose -f docker-compose.prod.yml logs -f

# Test dari luar
curl -I https://yourdomain.com
```

---

## 👤 Default Accounts

| Role | Email | Password |
|------|-------|----------|
| ROOT (Admin) | admin@yuhlez.com | 12345678 |

> Akun COMPANY dan INTERN dibuat otomatis via Google OAuth Login.
> Hanya akun ROOT yang bisa login dengan email/password.

---

## 🏗️ Architecture

```
┌────────────────────────────────────────────────────────┐
│                     VPS (Ubuntu)                       │
│                                                        │
│  ┌──────────────────────────────────────────────────┐  │
│  │              Nginx (Port 80/443)                 │  │
│  │           SSL Termination + Static Files         │  │
│  └──────────────┬───────────────────────────────────┘  │
│                 │                                      │
│  ┌──────────────▼───────────────────────────────────┐  │
│  │         Laravel App - PHP-FPM (Port 9000)        │  │
│  │  ┌─────────┐ ┌──────────┐ ┌─────────────────┐   │  │
│  │  │  Queue   │ │Scheduler │ │  File Storage   │   │  │
│  │  │ Worker   │ │ (cron)   │ │ (public disk)   │   │  │
│  │  └────┬─────┘ └────┬─────┘ └─────────────────┘   │  │
│  └───────┼─────────────┼────────────────────────────┘  │
│          │             │                               │
│  ┌───────▼─────────────▼────────────────────────────┐  │
│  │  ┌──────────────┐  ┌──────────────────────────┐  │  │
│  │  │  MariaDB      │  │  Redis                    │  │  │
│  │  │  (Port 3306)  │  │  (Cache + Queue + Session)│  │  │
│  │  └──────────────┘  └──────────────────────────┘  │  │
│  └──────────────────────────────────────────────────┘  │
└────────────────────────────────────────────────────────┘
```

---

## 🔧 Troubleshooting

### Database Connection Error

```bash
# Cek MySQL running
docker compose -f docker-compose.prod.yml ps mysql

# Cek log
docker compose -f docker-compose.prod.yml logs mysql

# Restart
docker compose -f docker-compose.prod.yml restart mysql
```

### Permission Error

```bash
docker compose -f docker-compose.prod.yml exec app \
  chown -R www-data:www-data storage bootstrap/cache
```

### Queue Tidak Jalan

```bash
# Cek status queue worker
docker compose -f docker-compose.prod.yml ps queue

# Restart
docker compose -f docker-compose.prod.yml restart queue

# Cek log
docker compose -f docker-compose.prod.yml logs -f queue
```

### Email Tidak Terkirim

1. Pastikan MAIL_MAILER=smtp (bukan log)
2. Pastikan SMTP credentials benar di .env
3. Cek log: `tail -f storage/logs/laravel.log`
4. Jalankan queue worker: `php artisan queue:work`

### Google OAuth Error

1. Pastikan redirect URI sesuai:
   - Local: `http://127.0.0.1:8000/v1/auth/google/callback`
   - VPS: `https://yourdomain.com/v1/auth/google/callback`
2. Pastikan GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET benar
3. Cek Google Console → OAuth consent screen sudah published

---

## 💾 Backup

```bash
# Backup database
docker compose -f docker-compose.prod.yml exec mysql \
  mysqldump -u root -p yuhlez > backup_$(date +%Y%m%d_%H%M).sql

# Backup storage (file uploads, CV, sertifikat)
tar -czf storage_backup_$(date +%Y%m%d).tar.gz storage/app/public/

# Restore database
docker compose -f docker-compose.prod.yml exec -T mysql \
  mysql -u root -pDB_PASSWORD yuhlez < backup.sql
```

---

## 📊 Useful Commands

```bash
# Jalankan artisan
docker compose -f docker-compose.prod.yml exec app php artisan [command]

# Common commands:
php artisan migrate              # Jalankan migrasi
php artisan migrate:rollback     # Rollback migrasi terakhir
php artisan db:seed              # Seed database
php artisan cache:clear          # Clear cache
php artisan config:cache         # Cache config
php artisan route:cache          # Cache routes
php artisan view:cache           # Cache views
php artisan optimize:clear       # Clear semua cache
php artisan queue:work           # Jalankan queue worker
php artisan queue:restart        # Restart queue worker

# Logs
tail -f storage/logs/laravel.log
```
