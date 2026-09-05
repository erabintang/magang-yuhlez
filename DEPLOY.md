# Deploy YUHLEZ (Tanpa npm)

## Prasyarat Server
- PHP 8.2+
- Composer
- MySQL/PostgreSQL
- Apache2 atau Nginx

## Langkah Deploy

```bash
# 1. Clone project
cd /var/www/
git clone <repo-url> yuhlez
cd yuhlez

# 2. Install PHP dependencies
composer install --no-dev --optimize-autoloader

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Edit .env — isi database credentials
nano .env
```

Edit bagian ini di `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yuhlez
DB_USERNAME=root
DB_PASSWORD=your_password

APP_URL=https://yourdomain.com
APP_ENV=production
APP_DEBUG=false
```

```bash
# 5. Jalankan migrasi
php artisan migrate --force

# 6. Set permissions
chown -R www-data:www-data /var/www/yuhlez
chmod -R 755 /var/www/yuhlez/storage
chmod -R 755 /var/www/yuhlez/bootstrap/cache

# 7. Cache optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Buat storage link
php artisan storage:link
```

## Konfigurasi Apache

Buat file `/etc/apache2/sites-available/yuhlez.conf`:

```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /var/www/yuhlez/public

    <Directory /var/www/yuhlez/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/yuhlez-error.log
    CustomLog ${APACHE_LOG_DIR}/yuhlez-access.log combined
</VirtualHost>
```

```bash
# Aktifkan site
sudo a2ensite yuhlez.conf
sudo a2dissite 000-default.conf
sudo systemctl restart apache2
```

## Konfigurasi Nginx

Buat file `/etc/nginx/sites-available/yuhlez`:

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/yuhlez/public;

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
# Aktifkan site
sudo ln -s /etc/nginx/sites-available/yuhlez /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl restart nginx
```

## SSL (Let's Encrypt)

```bash
sudo certbot --nginx -d yourdomain.com -d www.yuhlez.com
```

## Yang TIDAK Perlu
- ❌ `npm install`
- ❌ `npm run build`
- ❌ `vite.config.js`
- ❌ `package.json`
- ❌ `node_modules/`
- ❌ `public/build/`

## Yang Sudah Tersedia (CDN)
- ✅ Tailwind CSS → `cdn.tailwindcss.com`
- ✅ Trix Editor → `unpkg.com/trix`
- ✅ Chart.js → `cdn.jsdelivr.net`
- ✅ Axios → `cdn.jsdelivr.net`
- ✅ Custom CSS → `public/css/app.css`
- ✅ Custom JS → `public/js/app.js`

## Cek Status
```bash
php artisan route:list
php artisan test
tail -f storage/logs/laravel.log
```
