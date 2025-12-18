#!/bin/bash
set -e

echo "🚀 Starting Zaf's Kitchen Application..."

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

# ✅ ENABLE FULL ERROR DISPLAY FOR DEBUGGING
echo "✅ Enabling FULL error logging and display..."
cat > /usr/local/etc/php/conf.d/error-logging.ini << EOF
display_errors = On
display_startup_errors = On
log_errors = On
error_reporting = E_ALL
html_errors = On
EOF

echo "✅ Application ready"
echo "🌐 Starting PHP built-in server on port ${PORT:-8080}"

# Start PHP built-in server with router script
exec php -S 0.0.0.0:${PORT:-8080} -t /var/www/html index.php
