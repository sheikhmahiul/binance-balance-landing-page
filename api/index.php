<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain');
    echo "EX: " . $e->getMessage() . "\n";
    echo "TRACE:\n" . $e->getTraceAsString();
    exit;
}











