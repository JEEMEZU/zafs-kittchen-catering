<?php
$DATABASE_URL = getenv('DATABASE_URL');

if (!$DATABASE_URL) {
    error_log("❌ DATABASE_URL not set");
    die("Database configuration error");
}

try {
    $url = parse_url($DATABASE_URL);
    
    if (!$url) {
        throw new Exception("Invalid DATABASE_URL format");
    }
    
    $host = $url['host'] ?? '';
    $port = $url['port'] ?? 5432;
    $dbname = ltrim($url['path'] ?? '', '/');
    $user = $url['user'] ?? '';
    $password = $url['pass'] ?? '';
    
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    
    // Check pgsql driver
    $available_drivers = PDO::getAvailableDrivers();
    
    if (!in_array('pgsql', $available_drivers)) {
        throw new Exception("PostgreSQL PDO driver not installed!");
    }
    
    $conn = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
    
    // Log success (not displayed)
    error_log("✅ Connected to PostgreSQL: $dbname");
    
} catch(PDOException $e) {
    error_log("❌ DB Connection Failed: " . $e->getMessage());
    die("Database connection error");
} catch(Exception $e) {
    error_log("❌ Config Error: " . $e->getMessage());
    die("Configuration error");
}