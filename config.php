<?php
define('DB_HOST', 'pg-af83573-filehub2025.h.aivencloud.com');
define('DB_PORT', '26302');
define('DB_NAME', 'defaultdb');
define('DB_USER', 'avnadmin');
define('DB_PASSWORD', 'AVNS_7u3XP8zXScutl073Z6K');

try {
    $pdo = new PDO(
        "pgsql:host=" . DB_HOST .
        ";port=" . DB_PORT .
        ";dbname=" . DB_NAME .
        ";sslmode=require",
        DB_USER,
        DB_PASSWORD,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("PostgreSQL connection failed: " . $e->getMessage());
}
