<?php
// Simple router for PHP built-in server
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Route PHP files
if (preg_match('/\.php$/', $uri)) {
    $file = __DIR__ . $uri;
    if (file_exists($file)) {
        include $file;
        return true;
    }
}

// Default to index.php
if ($uri === '/' || $uri === '') {
    include __DIR__ . '/index.php';
    return true;
}

// File not found
http_response_code(404);
echo "404 - File not found";
return true;