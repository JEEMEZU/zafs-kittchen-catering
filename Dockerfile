FROM php:8.2-apache

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

# Disable conflicting MPM modules and enable only mpm_prefork
RUN a2dismod mpm_event mpm_worker && a2enmod mpm_prefork

# Enable Apache modules
RUN a2enmod rewrite headers

# Set working directory
WORKDIR /var/www/html

# Copy startup script FIRST
COPY docker-start.sh /usr/local/bin/docker-start.sh
RUN chmod +x /usr/local/bin/docker-start.sh

# Copy composer files first
COPY composer.json ./

# Remove old composer.lock and regenerate
RUN rm -f composer.lock

# Install ALL dependencies from composer.json
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy all files
COPY . .

# ✅ UPDATED: Verify Resend installation
RUN php -r "require 'vendor/autoload.php'; \
    echo 'Checking installations...\n'; \
    echo 'Resend: ' . (class_exists('Resend\Client') ? '✅' : '❌') . '\n'; \
    echo 'Google_Client: ' . (class_exists('Google_Client') ? '✅' : '❌') . '\n'; \
    echo 'Dotenv: ' . (class_exists('Dotenv\Dotenv') ? '✅' : '❌') . '\n';"

# Set permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Production PHP config
RUN { \
        echo 'display_errors = Off'; \
        echo 'log_errors = On'; \
        echo 'error_log = /var/log/apache2/php_errors.log'; \
        echo 'error_reporting = E_ALL'; \
        echo 'session.cookie_httponly = 1'; \
        echo 'session.use_strict_mode = 1'; \
    } > /usr/local/etc/php/conf.d/production.ini

# Apache config for Railway
ENV PORT=8080
RUN sed -i 's/Listen 80/Listen ${PORT}/g' /etc/apache2/ports.conf && \
    sed -i 's/:80/:${PORT}/g' /etc/apache2/sites-available/000-default.conf

# Directory config
RUN echo '<Directory /var/www/html/>' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    Options -Indexes +FollowSymLinks' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    AllowOverride All' >> /etc/apache2/sites-available/000-default.conf && \
    echo '    Require all granted' >> /etc/apache2/sites-available/000-default.conf && \
    echo '</Directory>' >> /etc/apache2/sites-available/000-default.conf

EXPOSE ${PORT}

# Use startup script as entrypoint
CMD ["/usr/local/bin/docker-start.sh"]
