<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (is_dir('/tmp') || PHP_OS_FAMILY !== 'Windows') {
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

    putenv('SESSION_DRIVER=array');
    $_ENV['SESSION_DRIVER'] = 'array';
    $_SERVER['SESSION_DRIVER'] = 'array';

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

    putenv('FILESYSTEM_DISK=local');
    $_ENV['FILESYSTEM_DISK'] = 'local';
    $_SERVER['FILESYSTEM_DISK'] = 'local';

    if (!getenv('APP_KEY') && empty($_ENV['APP_KEY'])) {
        $fallbackKey = 'base64:y4w87HLEXPHwl6HNufIF4+E+sMeef9OPT8srgErDTSQ=';
        putenv("APP_KEY={$fallbackKey}");
        $_ENV['APP_KEY'] = $fallbackKey;
        $_SERVER['APP_KEY'] = $fallbackKey;
    }

    $sqlitePath = '/tmp/database.sqlite';
    if (!file_exists($sqlitePath)) {
        @touch($sqlitePath);
    }
    putenv("DB_CONNECTION=sqlite");
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_SERVER['DB_CONNECTION'] = 'sqlite';

    putenv("DB_DATABASE={$sqlitePath}");
    $_ENV['DB_DATABASE'] = $sqlitePath;
    $_SERVER['DB_DATABASE'] = $sqlitePath;
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());

