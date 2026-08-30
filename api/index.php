<?php

putenv('APP_ENV=production');
putenv('APP_DEBUG=true');
putenv('APP_KEY=base64:y4w87HLEXPHwl6HNufIF4+E+sMeef9OPT8srgErDTSQ=');
putenv('SESSION_DRIVER=array');
putenv('CACHE_STORE=array');
putenv('CACHE_DRIVER=array');
putenv('LOG_CHANNEL=stderr');
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=/tmp/database.sqlite');
putenv('QUEUE_CONNECTION=sync');
putenv('MAIL_MAILER=log');

$_ENV['APP_ENV'] = 'production';
$_ENV['APP_DEBUG'] = 'true';
$_ENV['APP_KEY'] = 'base64:y4w87HLEXPHwl6HNufIF4+E+sMeef9OPT8srgErDTSQ=';
$_ENV['SESSION_DRIVER'] = 'array';
$_ENV['CACHE_STORE'] = 'array';
$_ENV['CACHE_DRIVER'] = 'array';
$_ENV['LOG_CHANNEL'] = 'stderr';
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = '/tmp/database.sqlite';
$_ENV['QUEUE_CONNECTION'] = 'sync';
$_ENV['MAIL_MAILER'] = 'log';

$_SERVER['APP_ENV'] = 'production';
$_SERVER['APP_DEBUG'] = 'true';
$_SERVER['APP_KEY'] = 'base64:y4w87HLEXPHwl6HNufIF4+E+sMeef9OPT8srgErDTSQ=';
$_SERVER['SESSION_DRIVER'] = 'array';
$_SERVER['CACHE_STORE'] = 'array';
$_SERVER['CACHE_DRIVER'] = 'array';
$_SERVER['LOG_CHANNEL'] = 'stderr';
$_SERVER['DB_CONNECTION'] = 'sqlite';
$_SERVER['DB_DATABASE'] = '/tmp/database.sqlite';
$_SERVER['QUEUE_CONNECTION'] = 'sync';
$_SERVER['MAIL_MAILER'] = 'log';

// Forward Vercel serverless requests to Laravel's public/index.php
require __DIR__ . '/../public/index.php';














