FROM php:8.2-cli

# Install dependencies
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
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Verify PostgreSQL
RUN php -m | grep -i pdo_pgsql || (echo "❌ pdo_pgsql not installed!" && exit 1)

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy startup script
COPY docker-start.sh /usr/local/bin/docker-start.sh
RUN chmod +x /usr/local/bin/docker-start.sh

# Copy composer files
COPY composer.json composer.lock* ./

# Install dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction || \
    (rm -f composer.lock && composer install --no-dev --optimize-autoloader --no-interaction)

# Copy all files
COPY . .

# Verify installations
RUN php -r "require 'vendor/autoload.php'; \
    echo 'Checking installations...\n'; \
    echo 'Resend: ' . (class_exists('Resend\Client') ? '✅' : '❌') . '\n'; \
    echo 'Google_Client: ' . (class_exists('Google_Client') ? '✅' : '❌') . '\n'; \
    echo 'Dotenv: ' . (class_exists('Dotenv\Dotenv') ? '✅' : '❌') . '\n';" || true

# Set permissions
RUN chmod -R 755 /var/www/html

# Production PHP config
RUN { \
        echo 'display_errors = On'; \
        echo 'log_errors = On'; \
        echo 'error_reporting = E_ALL'; \
        echo 'session.cookie_httponly = 1'; \
        echo 'session.use_strict_mode = 1'; \
    } > /usr/local/etc/php/conf.d/production.ini

# Set port
ENV PORT=8080

EXPOSE ${PORT}

# Use startup script
CMD ["/usr/local/bin/docker-start.sh"]
