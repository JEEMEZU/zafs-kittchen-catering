#!/bin/bash
set -e

echo "🚀 Starting Zaf's Kitchen Application..."

<<<<<<< HEAD
# Check if DATABASE_URL is set
if [ -z "$DATABASE_URL" ]; then
    echo "❌ ERROR: DATABASE_URL environment variable not set"
    exit 1
fi

echo "✅ DATABASE_URL is configured"

# Check environment variables
echo "Checking environment variables..."
echo "RESEND_API_KEY: $([ -n "$RESEND_API_KEY" ] && echo "Set" || echo "Missing")"

# Check PostgreSQL PDO driver
php -m | grep -i pdo_pgsql > /dev/null
if [ $? -eq 0 ]; then
    echo "✅ PostgreSQL PDO driver loaded"
else
    echo "❌ PostgreSQL PDO driver NOT found"
    exit 1
fi

# Verify vendor directory
if [ ! -d "vendor" ]; then
    echo "❌ Vendor directory not found. Running composer install..."
    composer install --no-dev --optimize-autoloader
fi

# Test PHP files
echo "Testing PHP files..."
php -l connection.php || echo "❌ connection.php has syntax errors"
php -l sendmail.php || echo "❌ sendmail.php has syntax errors"
php -l controllerUserData.php || echo "❌ controllerUserData.php has syntax errors"

# Set correct permissions
chown -R www-data:www-data /var/www/html
chmod -R 755 /var/www/html

# ✅ ENABLE FULL ERROR DISPLAY FOR DEBUGGING
echo "✅ Enabling FULL error logging and display..."
cat > /usr/local/etc/php/conf.d/error-logging.ini << EOF
display_errors = On
display_startup_errors = On
log_errors = On
error_reporting = E_ALL
error_log = /proc/self/fd/2
html_errors = On
EOF

echo "✅ Application ready"
echo "🌐 Starting Apache on port ${PORT:-8080}"

# Start Apache in foreground
exec apache2-foreground
=======
if [ -z "$DATABASE_URL" ]; then
    echo "❌ ERROR: DATABASE_URL not set"
    exit 1
fi

echo "✅ DATABASE_URL configured"

# Check PostgreSQL
php -m | grep -i pdo_pgsql > /dev/null || exit 1
echo "✅ PostgreSQL PDO loaded"

# Install composer dependencies if needed
if [ ! -d "vendor" ]; then
    composer install --no-dev --optimize-autoloader
fi

echo "✅ Application ready"
echo "🌐 Starting PHP server on port ${PORT:-8080}"

# Start with router
cd /var/www/html
exec php -S 0.0.0.0:${PORT:-8080} router.php
>>>>>>> 7736cfa5f6308ce124ef3ff71fe71072d8b57f51
