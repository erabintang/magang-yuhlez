# 🚀 YUHLEZ Laravel - VPS Deployment Guide

## Spesifikasi Minimum VPS

| Komponen | Minimum | Recommended |
|---|---|---|
| **OS** | Ubuntu 22.04 LTS | Ubuntu 24.04 LTS |
| **RAM** | 1 GB | 2 GB |
| **CPU** | 1 vCPU | 2 vCPU |
| **Storage** | 20 GB SSD | 40 GB SSD |
| **PHP** | 8.2 | 8.2+ |
| **Web Server** | Nginx | Nginx |
| **Database** | Supabase PostgreSQL | Supabase PostgreSQL |
| **Storage** | Supabase Storage | Supabase Storage |

---

## Yang Perlu Anda Siapkan SEBELUM Deploy

### 1. Database (Supabase)
- [ ] Buka Supabase Dashboard
- [ ] Catat **Host**, **Port**, **Database Name**, **Username**, **Password**
- [ ] Pastikan tabel sudah ada (profiles, company_profiles, intern_profiles, dll)
- [ ] Jalankan migration untuk tambah kolom yang belum ada:
  ```bash
  cd yuhlez-laravel
  php artisan migrate --force
  ```

### 2. Supabase Storage
- [ ] Pastikan bucket `yuhlez` sudah ada di Supabase Storage
- [ ] Catat **SUPABASE_URL**, **SUPABASE_ANON_KEY**, **SUPABASE_SERVICE_ROLE_KEY**

### 3. Google OAuth
- [ ] Buka [Google Cloud Console](https://console.cloud.google.com)
- [ ] Buka menu **APIs & Services → Credentials**
- [ ] Edit OAuth 2.0 Client ID yang sudah ada
- [ ] Tambahkan redirect URI: `https://yuhlez.com/v1/auth/google/callback`
- [ ] Catat **GOOGLE_CLIENT_ID** dan **GOOGLE_CLIENT_SECRET**

### 4. Email (SMTP Gmail)
- [ ] Buka Google Account → Security → 2-Step Verification
- [ ] Buka [App Passwords](https://myaccount.google.com/apppasswords)
- [ ] Buat App Password untuk "Mail"
- [ ] Catat **MAIL_USERNAME** (gmail.com) dan **MAIL_PASSWORD** (app password 16 digit)

### 5. Domain
- [ ] Beli domain (yuhlez.com atau lainnya)
- [ ] Point DNS ke IP VPS Anda:
  ```
  A Record    → IP_VPS_KAMU
  CNAME www   → yuhlez.com
  ```

---

## Cara Deploy

### Method 1: Manual (Recommended untuk pertama kali)

```bash
# 1. SSH ke VPS
ssh root@IP_VPS_KAMU

# 2. Install dependencies
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx php8.2-fpm php8.2-pgsql php8.2-mbstring php8.2-xml php8.2-curl php8.2-zip php8.2-gd unzip git curl supervisor redis-server certbot python3-certbot-nginx

# 3. Buat direktori
sudo mkdir -p /var/www/yuhlez
sudo chown $USER:$USER /var/www/yuhlez

# 4. Upload file (dari komputer lokal)
# Buka terminal lokal, jalankan:
rsync -avz --progress \
  --exclude='.env' \
  --exclude='node_modules' \
  --exclude='vendor' \
  --exclude='.git' \
  yuhlez-laravel/ root@IP_VPS:/var/www/yuhlez/

# 5. SSH lagi ke VPS
ssh root@IP_VPS

# 6. Setup
cd /var/www/yuhlez
cp deploy/.env.production.example .env
nano .env  # EDIT semua credentials!

# 7. Generate key
php artisan key:generate

# 8. Install PHP dependencies
composer install --no-dev --optimize-autoloader

# 9. Run migrations
php artisan migrate --force

# 10. Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 11. Set permissions
sudo chown -R www-data:www-data /var/www/yuhlez
sudo chmod -R 755 /var/www/yuhlez/storage

# 12. Setup Nginx
sudo cp deploy/nginx.conf /etc/nginx/sites-available/yuhlez
sudo ln -sf /etc/nginx/sites-available/yuhlez /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl restart nginx

# 13. Setup Supervisor
sudo cp deploy/supervisor.conf /etc/supervisor/conf.d/yuhlez.conf
sudo supervisorctl reread
sudo supervisorctl update

# 14. Setup SSL
sudo certbot --nginx -d yuhlez.com -d www.yuhlez.com

# 15. Setup cron
(crontab -l 2>/dev/null; echo "* * * * * cd /var/www/yuhlez && php artisan schedule:run >> /dev/null 2>&1") | crontab -

# 16. Test
curl -I https://yuhlez.com
```

### Method 2: One-Click Deploy

```bash
# Upload deploy/deploy.sh ke VPS
scp deploy/deploy.sh root@IP_VPS:/root/
ssh root@IP_VPS
chmod +x /root/deploy.sh
sudo /root/deploy.sh
```

---

## Checklist Setelah Deploy

- [ ] Website bisa diakses: `https://yuhlez.com`
- [ ] Login dengan Google OAuth berfungsi
- [ ] Login dengan email/password berfungsi
- [ ] Dashboard admin bisa diakses
- [ ] Upload file (foto, CV, logo) berfungsi
- [ ] Email terkirim (reset password, notifikasi)
- [ ] Program magang bisa dibuat dan dilihat
- [ ] Pendaftaran magang berfungsi
- [ ] Sertifikat bisa diupload dan didownload
- [ ] Karya bisa dibuat dan tampil di landing page
- [ ] Mobile responsive
- [ ] SSL active (https)
- [ ] Tidak ada error di log: `tail -f storage/logs/laravel.log`

---

## Monitoring

```bash
# Cek status services
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status redis-server
sudo supervisorctl status

# Cek log error
tail -f /var/www/yuhlez/storage/logs/laravel.log

# Cek access log
tail -f /var/log/nginx/access.log

# Cek error log
tail -f /var/log/nginx/error.log

# Cek disk usage
df -h

# Cek memory
free -m

# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
sudo supervisorctl restart all
```

---

## Update Code

```bash
cd /var/www/yuhlez

# Upload file baru
rsync -avz --exclude='.env' --exclude='node_modules' --exclude='vendor' yuhlez-laravel/ root@IP_VPS:/var/www/yuhlez/

# Atau git pull
git pull origin main

# Jalankan update
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo chown -R www-data:www-data /var/www/yuhlez
sudo supervisorctl restart yuhlez-queue:*
```

---

## Troubleshooting

| Masalah | Solusi |
|---|---|
| 500 Internal Server Error | Cek `storage/logs/laravel.log` |
| CSRF token mismatch | Clear session: `php artisan config:clear` |
| Google OAuth error | Cek redirect URI di Google Cloud Console |
| Email tidak terkirim | Cek SMTP config di `.env` |
| File upload gagal | Cek Supabase credentials di `.env` |
| Slow response | Jalankan: `php artisan config:cache && php artisan route:cache` |
| Nginx 502 Bad Gateway | `sudo systemctl restart php8.2-fpm` |
| Migration error | Cek Supabase DB connection di `.env` |

---

## File Structure di VPS

```
/var/www/yuhlez/
├── app/                    # PHP Controllers, Models, Services
├── bootstrap/              # Laravel bootstrap
├── config/                 # Laravel config
├── database/               # Migrations, Seeders
├── deploy/                 # Deployment configs
│   ├── .env.production.example
│   ├── nginx.conf
│   ├── supervisor.conf
│   └── deploy.sh
├── public/                 # Web root (index.php)
│   ├── build/              # Vite production build
│   ├── js/                 # chunked-upload.js, wysiwyg.js
│   └── brand/              # Logo, team photos
├── resources/              # Blade views, CSS, JS
├── routes/                 # web.php
├── storage/                # Logs, cache, sessions
├── vendor/                 # Composer dependencies
├── .env                    # Environment (SECRET!)
└── composer.json
```

---

**Siap deploy saat VPS aktif! 🚀**
