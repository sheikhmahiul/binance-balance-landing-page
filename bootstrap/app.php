<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

if (isset($_ENV['VERCEL']) || getenv('VERCEL') || isset($_SERVER['VERCEL']) || is_dir('/tmp')) {
    $staleCaches = [
        __DIR__ . '/cache/config.php',
        __DIR__ . '/cache/routes-v7.php',
        __DIR__ . '/cache/services.php',
        __DIR__ . '/cache/packages.php',
        __DIR__ . '/cache/events.php',
    ];

    foreach ($staleCaches as $staleCache) {
        if (file_exists($staleCache)) {
            @unlink($staleCache);
        }
    }

    $dirs = [
        '/tmp/storage/app/public',
        '/tmp/storage/framework/views',
        '/tmp/storage/framework/sessions',
        '/tmp/storage/framework/cache/data',
        '/tmp/storage/logs',
    ];

    foreach ($dirs as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }

    putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
    $_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';
    $_SERVER['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

    putenv('APP_CONFIG_CACHE=/tmp/no_config.php');
    $_ENV['APP_CONFIG_CACHE'] = '/tmp/no_config.php';
    $_SERVER['APP_CONFIG_CACHE'] = '/tmp/no_config.php';

    putenv('APP_SERVICES_CACHE=/tmp/no_services.php');
    $_ENV['APP_SERVICES_CACHE'] = '/tmp/no_services.php';
    $_SERVER['APP_SERVICES_CACHE'] = '/tmp/no_services.php';

    putenv('APP_PACKAGES_CACHE=/tmp/no_packages.php');
    $_ENV['APP_PACKAGES_CACHE'] = '/tmp/no_packages.php';
    $_SERVER['APP_PACKAGES_CACHE'] = '/tmp/no_packages.php';

    putenv('APP_ROUTES_CACHE=/tmp/no_routes.php');
    $_ENV['APP_ROUTES_CACHE'] = '/tmp/no_routes.php';
    $_SERVER['APP_ROUTES_CACHE'] = '/tmp/no_routes.php';

    putenv('APP_EVENTS_CACHE=/tmp/no_events.php');
    $_ENV['APP_EVENTS_CACHE'] = '/tmp/no_events.php';
    $_SERVER['APP_EVENTS_CACHE'] = '/tmp/no_events.php';

    if (PHP_SAPI !== 'cli') {
        if (empty(getenv('SESSION_DRIVER')) || empty($_ENV['SESSION_DRIVER'])) {
            putenv('SESSION_DRIVER=cookie');
            $_ENV['SESSION_DRIVER'] = 'cookie';
            $_SERVER['SESSION_DRIVER'] = 'cookie';
        }
    }

    putenv('CACHE_STORE=array');
    $_ENV['CACHE_STORE'] = 'array';
    $_SERVER['CACHE_STORE'] = 'array';

    putenv('LOG_CHANNEL=stderr');
    $_ENV['LOG_CHANNEL'] = 'stderr';
    $_SERVER['LOG_CHANNEL'] = 'stderr';

    if (!getenv('APP_KEY') && empty($_ENV['APP_KEY'])) {
        $fallbackKey = 'base64:y4w87HLEXPHwl6HNufIF4+E+sMeef9OPT8srgErDTSQ=';
        putenv("APP_KEY={$fallbackKey}");
        $_ENV['APP_KEY'] = $fallbackKey;
        $_SERVER['APP_KEY'] = $fallbackKey;
    }

    $dbConnection = getenv('DB_CONNECTION') ?: ($_ENV['DB_CONNECTION'] ?? 'sqlite');
    if ($dbConnection === 'sqlite') {
        $sqlitePath = '/tmp/database.sqlite';
        if (!file_exists($sqlitePath)) {
            @touch($sqlitePath);
        }
        putenv("DB_DATABASE={$sqlitePath}");
        $_ENV['DB_DATABASE'] = $sqlitePath;
        $_SERVER['DB_DATABASE'] = $sqlitePath;
    }
}


$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();

$app->register(\Illuminate\View\ViewServiceProvider::class);
$app->register(\Illuminate\Session\SessionServiceProvider::class);
$app->register(\Illuminate\Filesystem\FilesystemServiceProvider::class);

if (isset($_ENV['VERCEL']) || getenv('VERCEL') || isset($_SERVER['VERCEL']) || is_dir('/tmp')) {
    $app->useStoragePath('/tmp/storage');
}

return $app;







