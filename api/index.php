<?php

if (isset($_GET['debug_env'])) {
    header('Content-Type: text/plain');
    echo "PHP_SAPI: " . PHP_SAPI . "\n";
    echo "PHP_OS_FAMILY: " . PHP_OS_FAMILY . "\n";
    print_r($_SERVER);
    print_r($_ENV);
    exit;
}

require __DIR__ . '/../public/index.php';












