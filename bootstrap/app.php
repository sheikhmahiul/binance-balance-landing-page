<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

if (isset($_ENV['VERCEL']) || getenv('VERCEL') || isset($_SERVER['VERCEL']) || is_dir('/tmp')) {
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

    putenv('APP_CONFIG_CACHE=/tmp/non_existent_config.php');
    $_ENV['APP_CONFIG_CACHE'] = '/tmp/non_existent_config.php';
    $_SERVER['APP_CONFIG_CACHE'] = '/tmp/non_existent_config.php';

    putenv('APP_SERVICES_CACHE=/tmp/non_existent_services.php');
    $_ENV['APP_SERVICES_CACHE'] = '/tmp/non_existent_services.php';
    $_SERVER['APP_SERVICES_CACHE'] = '/tmp/non_existent_services.php';

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

if (isset($_ENV['VERCEL']) || getenv('VERCEL') || isset($_SERVER['VERCEL']) || is_dir('/tmp')) {
    $app->useStoragePath('/tmp/storage');
}

return $app;






