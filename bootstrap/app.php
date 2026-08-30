<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

if (is_dir('/tmp') || PHP_OS_FAMILY !== 'Windows') {
    $envKeys = ['SESSION_DRIVER', 'CACHE_STORE', 'CACHE_DRIVER', 'LOG_CHANNEL', 'DB_CONNECTION', 'QUEUE_CONNECTION', 'MAIL_MAILER', 'BROADCAST_CONNECTION'];
    foreach ($envKeys as $key) {
        if (isset($_ENV[$key]) && $_ENV[$key] === '') {
            unset($_ENV[$key]);
        }
        if (isset($_SERVER[$key]) && $_SERVER[$key] === '') {
            unset($_SERVER[$key]);
        }
        if (getenv($key) === '') {
            putenv($key);
        }
    }

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
        '/tmp/storage/framework',
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

    putenv('APP_PACKAGES_CACHE=/tmp/storage/framework/packages.php');
    $_ENV['APP_PACKAGES_CACHE'] = '/tmp/storage/framework/packages.php';
    $_SERVER['APP_PACKAGES_CACHE'] = '/tmp/storage/framework/packages.php';

    putenv('APP_SERVICES_CACHE=/tmp/storage/framework/services.php');
    $_ENV['APP_SERVICES_CACHE'] = '/tmp/storage/framework/services.php';
    $_SERVER['APP_SERVICES_CACHE'] = '/tmp/storage/framework/services.php';

    putenv('APP_CONFIG_CACHE=/tmp/storage/framework/config.php');
    $_ENV['APP_CONFIG_CACHE'] = '/tmp/storage/framework/config.php';
    $_SERVER['APP_CONFIG_CACHE'] = '/tmp/storage/framework/config.php';

    putenv('APP_ROUTES_CACHE=/tmp/storage/framework/routes-v7.php');
    $_ENV['APP_ROUTES_CACHE'] = '/tmp/storage/framework/routes-v7.php';
    $_SERVER['APP_ROUTES_CACHE'] = '/tmp/storage/framework/routes-v7.php';

    putenv('APP_EVENTS_CACHE=/tmp/storage/framework/events.php');
    $_ENV['APP_EVENTS_CACHE'] = '/tmp/storage/framework/events.php';
    $_SERVER['APP_EVENTS_CACHE'] = '/tmp/storage/framework/events.php';

    if (PHP_SAPI !== 'cli') {
        putenv('SESSION_DRIVER=array');
        $_ENV['SESSION_DRIVER'] = 'array';
        $_SERVER['SESSION_DRIVER'] = 'array';
    }

    putenv('CACHE_STORE=array');
    $_ENV['CACHE_STORE'] = 'array';
    $_SERVER['CACHE_STORE'] = 'array';

    putenv('CACHE_DRIVER=array');
    $_ENV['CACHE_DRIVER'] = 'array';
    $_SERVER['CACHE_DRIVER'] = 'array';

    putenv('LOG_CHANNEL=stderr');
    $_ENV['LOG_CHANNEL'] = 'stderr';
    $_SERVER['LOG_CHANNEL'] = 'stderr';

    putenv('QUEUE_CONNECTION=sync');
    $_ENV['QUEUE_CONNECTION'] = 'sync';
    $_SERVER['QUEUE_CONNECTION'] = 'sync';

    putenv('MAIL_MAILER=log');
    $_ENV['MAIL_MAILER'] = 'log';
    $_SERVER['MAIL_MAILER'] = 'log';

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

if (is_dir('/tmp') || PHP_OS_FAMILY !== 'Windows') {
    $app->useStoragePath('/tmp/storage');
    $app->singleton(\Illuminate\Foundation\PackageManifest::class, fn () => new \Illuminate\Foundation\PackageManifest(
        new \Illuminate\Filesystem\Filesystem,
        $app->basePath(),
        '/tmp/storage/framework/packages.php'
    ));
}

$app->instance('config_loaded_from_cache', false);
$app->instance('routes.cached', false);
$app->instance('events.cached', false);

$app->register(\Illuminate\View\ViewServiceProvider::class);
$app->register(\Illuminate\Session\SessionServiceProvider::class);
$app->register(\Illuminate\Filesystem\FilesystemServiceProvider::class);

$app->booting(function () use ($app) {
    $sqlitePath = '/tmp/database.sqlite';
    if (is_dir('/tmp') || PHP_OS_FAMILY !== 'Windows') {
        if (!file_exists($sqlitePath)) {
            @touch($sqlitePath);
        }
    } else {
        $sqlitePath = database_path('database.sqlite');
    }

    config([
        'database.default' => config('database.default') ?: 'sqlite',
        'database.connections.sqlite' => array_merge([
            'driver' => 'sqlite',
            'url' => null,
            'database' => $sqlitePath,
            'prefix' => '',
            'foreign_key_constraints' => true,
        ], config('database.connections.sqlite', [])),
        'session.driver' => config('session.driver') ?: 'array',
        'cache.default' => config('cache.default') ?: 'array',
        'logging.default' => config('logging.default') ?: 'stderr',
        'auth.defaults.guard' => config('auth.defaults.guard') ?: 'web',
        'auth.defaults.passwords' => config('auth.defaults.passwords') ?: 'users',
    ]);
});


return $app;
