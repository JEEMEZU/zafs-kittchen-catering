FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && \
    apt-get install -y \
        libpq-dev \
        libzip-dev \
        zip \
        unzip \
        git \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        pgsql \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Verify PostgreSQL extension
RUN php -m | grep -i pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files first (for better caching)
COPY composer.json composer.lock* ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction || \
    (rm -f composer.lock && composer install --no-dev --optimize-autoloader --no-interaction)

# Copy application files
COPY . .

# Make scripts executable
RUN chmod +x docker-start.sh

# Set proper permissions
RUN chmod -R 755 /var/www/html

# Configure PHP
RUN { \
        echo 'display_errors = On'; \
        echo 'log_errors = On'; \
        echo 'error_reporting = E_ALL'; \
        echo 'memory_limit = 256M'; \
        echo 'upload_max_filesize = 10M'; \
        echo 'post_max_size = 10M'; \
    } > /usr/local/etc/php/conf.d/custom.ini

# Set environment
ENV PORT=8080
EXPOSE 8080

# Health check (optional)
HEALTHCHECK --interval=30s --timeout=3s --start-period=40s \
  CMD php -r "exit(0);"

# Start application
CMD ["./docker-start.sh"]