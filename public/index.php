<?php

// Configuration pour éviter les erreurs de headers
if (!headers_sent()) {
    ob_start();
}

// Augmenter les limites de performance
ini_set('max_execution_time', 300); // 5 minutes
ini_set('memory_limit', '512M');
ini_set('max_input_vars', 3000);

// Supprimer les avertissements OpenSSL
ini_set('openssl.cafile', '');

// Optimisations pour le développement
if (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'PHP') !== false) {
    ini_set('opcache.enable', '1');
    ini_set('opcache.memory_consumption', '128');
    ini_set('opcache.max_accelerated_files', '4000');
    ini_set('opcache.revalidate_freq', '60');
}
error_reporting(E_ALL & ~E_WARNING);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

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

// Flush le buffer si actif
if (ob_get_level() > 0) {
    ob_end_flush();
}
