<?php

if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

// 1. Create writable /tmp storage directories for Vercel serverless environment
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

// 2. Set environment defaults for Vercel serverless execution
putenv('VERCEL=1');
putenv('VIEW_COMPILED_PATH=/tmp/views');
putenv('LOG_CHANNEL=stderr');
putenv('SESSION_DRIVER=cookie');
putenv('CACHE_STORE=array');

// Fallback APP_KEY if missing in Vercel environment variables
if (!getenv('APP_KEY') && empty($_ENV['APP_KEY'])) {
    $fallbackKey = 'base64:y4w87HLEXPHwl6HNufIF4+E+sMeef9OPT8srgErDTSQ=';
    putenv("APP_KEY={$fallbackKey}");
    $_ENV['APP_KEY'] = $fallbackKey;
    $_SERVER['APP_KEY'] = $fallbackKey;
}

// Auto-initialize SQLite in /tmp if default database connection is used
$dbConnection = getenv('DB_CONNECTION') ?: ($_ENV['DB_CONNECTION'] ?? 'sqlite');
if ($dbConnection === 'sqlite') {
    $sqlitePath = '/tmp/database.sqlite';
    $isFresh = !file_exists($sqlitePath) || filesize($sqlitePath) === 0;
    if (!file_exists($sqlitePath)) {
        @touch($sqlitePath);
    }
    putenv("DB_DATABASE={$sqlitePath}");
    $_ENV['DB_DATABASE'] = $sqlitePath;
    $_SERVER['DB_DATABASE'] = $sqlitePath;
} else {
    $isFresh = false;
}

// 3. Register Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// 4. Bootstrap Laravel application
/** @var \Illuminate\Foundation\Application $app */
$app = require __DIR__ . '/../bootstrap/app.php';

// 5. Run migrations & seeders on fresh SQLite database
if ($isFresh && $dbConnection === 'sqlite') {
    try {
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
        $kernel->call('migrate', ['--force' => true]);
        $kernel->call('db:seed', ['--force' => true]);
    } catch (\Throwable $e) {
        // Suppress migration error if already initialized
    }
}

// 6. Handle incoming HTTP request with exception reporting for Vercel
try {
    $request = \Illuminate\Http\Request::capture();
    $app->handleRequest($request);
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/html; charset=utf-8');
    echo "<h2>Laravel Serverless Exception</h2>";
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line " . $e->getLine() . ")</p>";
    echo "<pre style='background:#111;color:#f88;padding:15px;border-radius:6px;overflow:auto;'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}



