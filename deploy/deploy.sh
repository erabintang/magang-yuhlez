#!/bin/bash
# ==========================================
# YUHLEZ LARAVEL - DEPLOYMENT SCRIPT
# ==========================================
# Run this ONCE when deploying to a fresh VPS
# Usage: chmod +x deploy.sh && sudo ./deploy.sh

set -e

echo "🚀 YUHLEZ Laravel Deployment - Starting..."

# ==========================================
# 1. SYSTEM DEPENDENCIES
# ==========================================
echo ""
echo "📦 Step 1: Installing system dependencies..."

apt update && apt upgrade -y
apt install -y \
    nginx \
    php8.2-fpm \
    php8.2-pgsql \
    php8.2-mbstring \
    php8.2-xml \
    php8.2-curl \
    php8.2-zip \
    php8.2-gd \
    php8.2-bcmath \
    php8.2-tokenizer \
    php8.2-dom \
    unzip \
    git \
    curl \
    supervisor \
    redis-server \
    certbot \
    python3-certbot-nginx

echo "✅ System dependencies installed"

# ==========================================
# 2. PHP CONFIGURATION
# ==========================================
echo ""
echo "⚙️ Step 2: Configuring PHP..."

# Increase upload limits and memory
sed -i 's/upload_max_filesize = .*/upload_max_filesize = 50M/' /etc/php/8.2/fpm/php.ini
sed -i 's/post_max_size = .*/post_max_size = 55M/' /etc/php/8.2/fpm/php.ini
sed -i 's/memory_limit = .*/memory_limit = 256M/' /etc/php/8.2/fpm/php.ini
sed -i 's/max_execution_time = .*/max_execution_time = 60/' /etc/php/8.2/fpm/php.ini

systemctl restart php8.2-fpm

echo "✅ PHP configured"

# ==========================================
# 3. CREATE APP DIRECTORY
# ==========================================
echo ""
echo "📁 Step 3: Creating application directory..."

mkdir -p /var/www/yuhlez
mkdir -p /var/www/yuhlez/storage/logs
mkdir -p /var/www/yuhlez/storage/framework/sessions
mkdir -p /var/www/yuhlez/storage/framework/views
mkdir -p /var/www/yuhlez/storage/framework/cache

echo "✅ Directory created"

# ==========================================
# 4. COPY FILES (or git clone)
# ==========================================
echo ""
echo "📋 Step 4: Copying application files..."

# Option A: If deploying from local (scp/rsync)
# rsync -avz --exclude='.env' --exclude='node_modules' --exclude='vendor' ./ yuhlez@your-vps:/var/www/yuhlez/

# Option B: If from git repository
# cd /var/www/yuhlez
# git clone https://github.com/your-repo/yuhlez-laravel.git .

# For now, assume files are already at /var/www/yuhlez
echo "  → Pastikan file project sudah ada di /var/www/yuhlez/"
echo "  → Gunakan rsync: rsync -avz --exclude='.env' --exclude='node_modules' ./ yuhlez@VPS_IP:/var/www/yuhlez/"

# ==========================================
# 5. ENVIRONMENT
# ==========================================
echo ""
echo "🔐 Step 5: Setting up environment..."

if [ ! -f /var/www/yuhlez/.env ]; then
    cp /var/www/yuhlez/deploy/.env.production.example /var/www/yuhlez/.env
    echo "  ⚠️  .env file created from template!"
    echo "  ⚠️  EDIT IT NOW: nano /var/www/yuhlez/.env"
    echo "  ⚠️  Set APP_KEY, DB credentials, GOOGLE OAuth, SMTP, etc."
    echo ""
    echo "  After editing, run:"
    echo "    cd /var/www/yuhlez && php artisan key:generate"
    echo "    cd /var/www/yuhlez && php artisan migrate --force"
    echo ""
fi

# ==========================================
# 6. COMPOSER
# ==========================================
echo ""
echo "📦 Step 6: Installing PHP dependencies..."

cd /var/www/yuhlez
if [ -f composer.json ]; then
    composer install --no-dev --optimize-autoloader --no-interaction
fi

echo "✅ Composer dependencies installed"

# ==========================================
# 7. LARAVEL SETUP
# ==========================================
echo ""
echo "⚙️ Step 7: Setting up Laravel..."

cd /var/www/yuhlez
php artisan key:generate --force 2>/dev/null || true
php artisan migrate --force
php artisan db:seed --force 2>/dev/null || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link 2>/dev/null || true
npm install && npm run build 2>/dev/null || true

# Set permissions
chown -R www-data:www-data /var/www/yuhlez
chmod -R 755 /var/www/yuhlez/storage
chmod -R 755 /var/www/yuhlez/bootstrap/cache

echo "✅ Laravel configured"

# ==========================================
# 8. NGINX
# ==========================================
echo ""
echo "🌐 Step 8: Configuring Nginx..."

cp /var/www/yuhlez/deploy/nginx.conf /etc/nginx/sites-available/yuhlez
ln -sf /etc/nginx/sites-available/yuhlez /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default

nginx -t
systemctl restart nginx
systemctl enable nginx

echo "✅ Nginx configured"

# ==========================================
# 9. SUPERVISOR
# ==========================================
echo ""
echo "🔄 Step 9: Configuring Supervisor..."

cp /var/www/yuhlez/deploy/supervisor.conf /etc/supervisor/conf.d/yuhlez.conf
supervisorctl reread
supervisorctl update
supervisorctl start yuhlez-queue:*
supervisorctl start yuhlez-scheduler

echo "✅ Supervisor configured"

# ==========================================
# 10. REDIS
# ==========================================
echo ""
echo "🔴 Step 10: Starting Redis..."

systemctl start redis-server
systemctl enable redis-server

echo "✅ Redis started"

# ==========================================
# 11. SSL (Let's Encrypt)
# ==========================================
echo ""
echo "🔒 Step 11: Setting up SSL..."

echo "  → Untuk SSL, jalankan:"
echo "    certbot --nginx -d yuhlez.com -d www.yuhlez.com"
echo "  → Auto-renewal sudah di-setup oleh certbot"

# ==========================================
# 12. CRON
# ==========================================
echo ""
echo "⏰ Step 12: Setting up cron for cache/queue..."

# Add Laravel scheduler to crontab (backup for supervisor)
(crontab -l 2>/dev/null; echo "* * * * * cd /var/www/yuhlez && php artisan schedule:run >> /dev/null 2>&1") | crontab -

echo "✅ Cron configured"

# ==========================================
# DONE
# ==========================================
echo ""
echo "==========================================="
echo "✅ DEPLOYMENT COMPLETE!"
echo "==========================================="
echo ""
echo "📋 NEXT STEPS:"
echo "  1. Edit .env: nano /var/www/yuhlez/.env"
echo "  2. Generate APP_KEY: php artisan key:generate"
echo "  3. Run migrations: php artisan migrate --force"
echo "  4. Setup SSL: certbot --nginx -d yuhlez.com"
echo "  5. Test: curl -I https://yuhlez.com"
echo ""
echo "📊 CHECK STATUS:"
echo "  php artisan route:list"
echo "  php artisan test"
echo "  tail -f storage/logs/laravel.log"
echo "  supervisorctl status"
echo ""
echo "🌐 URL: https://yuhlez.com"
