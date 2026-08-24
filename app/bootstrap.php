<?php
/**
 * Application bootstrap — shared by BOTH the public website and the admin
 * dashboard. Loads config, registers an autoloader, sets up error handling,
 * timezone and the DB connections container.
 */

declare(strict_types=1);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

/* ---- Config ---- */
$config = require BASE_PATH . '/config/config.php';
$GLOBALS['app_config'] = $config;

/* ---- Error handling ---- */
error_reporting(E_ALL);
if (!empty($config['app']['debug'])) {
    ini_set('display_errors', '1');
} else {
    ini_set('display_errors', '0');
}
ini_set('log_errors', '1');
ini_set('error_log', BASE_PATH . '/storage/logs/php-error.log');

date_default_timezone_set($config['app']['timezone'] ?? 'UTC');

/* ---- Simple PSR-4-ish autoloader for the App\ namespace ---- */
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }
    $relative = substr($class, strlen($prefix));
    $file = BASE_PATH . '/app/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

/* ---- Helper functions ---- */
require BASE_PATH . '/app/Core/helpers.php';

/* ---- Boot core services ---- */
\App\Core\Config::boot($config);
\App\Core\Database::boot($config['db']);

/* ---- Uncaught exception handler ---- */
set_exception_handler(function (\Throwable $e): void {
    $config = \App\Core\Config::all();
    error_log('[UNCAUGHT] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    if (!empty($config['app']['debug'])) {
        echo '<pre style="padding:20px;font-family:monospace;white-space:pre-wrap;">';
        echo htmlspecialchars($e->getMessage()) . "\n\n";
        echo htmlspecialchars($e->getTraceAsString());
        echo '</pre>';
    } else {
        echo '<h1>Something went wrong</h1><p>Please try again later.</p>';
    }
});
