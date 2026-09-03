# ============================================================
# YUHLEZ - PRODUCTION DOCKERFILE
# ============================================================
# Multi-stage build untuk image yang lebih kecil dan aman.
# PHP 8.2-FPM untuk kompatibilitas dengan Nginx.
#
# VPS NOTES:
#   - Build otomatis saat docker compose up --build
#   - Node.js tidak di-install (assets sudah built di host/CI)
#   - Tidak ada dev dependencies (Composer --no-dev)
# ============================================================

FROM php:8.2-fpm

# ── System Dependencies ───────────────────────────────────
# Install library yang dibutuhkan PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ── Composer ──────────────────────────────────────────────
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# ── PHP Dependencies (cached layer) ──────────────────────
# Copy composer files dulu agar Docker cache lebih efektif
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts \
    && composer dump-autoload --optimize

# ── Application Files ─────────────────────────────────────
COPY . .

# ── Post-Install ──────────────────────────────────────────
# Jalankan post-install scripts dan buat direktori yang dibutuhkan
RUN composer dump-autoload --optimize \
    && mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p storage/app/public \
    && mkdir -p bootstrap/cache

# ── Permissions ───────────────────────────────────────────
# www-data adalah user PHP-FPM, butuh akses ke storage & cache
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage/app/public

# ── PHP Configuration ─────────────────────────────────────
# Optimasi untuk production: upload 50MB, memory 256MB
RUN echo "upload_max_filesize = 50M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 50M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time = 300" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_input_time = 300" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.max_accelerated_files=10000" >> /usr/local/etc/php/conf.d/opcache.ini \
    && echo "opcache.revalidate_freq=0" >> /usr/local/etc/php/conf.d/opcache.ini

EXPOSE 9000

CMD ["php-fpm"]
