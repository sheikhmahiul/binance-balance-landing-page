<?php

// Prepare writable /tmp directories for Vercel serverless execution
$dirs = [
    '/tmp/storage/app/public',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/logs',
    '/tmp/views',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// Forward Vercel serverless requests to Laravel's public/index.php
require __DIR__ . '/../public/index.php';
