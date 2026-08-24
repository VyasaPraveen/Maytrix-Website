<?php
/**
 * Central configuration loader.
 * Reads the .env file (if present) and returns a typed config array.
 * No secrets are hard-coded here — everything comes from .env.
 */

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

/**
 * Minimal .env parser (no external dependency).
 */
function load_env(string $path): array
{
    $vars = [];
    if (!is_file($path)) {
        return $vars;
    }
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        // Strip surrounding quotes.
        if (strlen($value) >= 2 && ($value[0] === '"' || $value[0] === "'")) {
            $value = substr($value, 1, -1);
        }
        $vars[$key] = $value;
    }
    return $vars;
}

$env = load_env(BASE_PATH . '/.env');

/** Helper reads env with fallback + basic type coercion. */
$get = function (string $key, $default = null) use ($env) {
    $val = $env[$key] ?? getenv($key);
    if ($val === false || $val === null || $val === '') {
        return $default;
    }
    $lower = strtolower((string) $val);
    if ($lower === 'true')  return true;
    if ($lower === 'false') return false;
    if ($lower === 'null')  return null;
    return $val;
};

$driver = $get('DB_DRIVER', 'sqlite');

return [
    'app' => [
        'name'     => $get('APP_NAME', 'Maytrix Education'),
        'env'      => $get('APP_ENV', 'production'),
        'debug'    => (bool) $get('APP_DEBUG', false),
        'url'      => rtrim($get('APP_URL', ''), '/'),
        'timezone' => $get('APP_TIMEZONE', 'Asia/Kolkata'),
        'key'      => $get('APP_KEY', 'insecure-dev-key-change-me'),
        'session_name' => $get('SESSION_NAME', 'maytrix_sess'),
    ],

    'db' => [
        'driver' => $driver,
        // CONTENT / WEBSITE database
        'web' => [
            'driver' => $driver,
            'host'   => $get('DB_WEB_HOST', 'localhost'),
            'port'   => (int) $get('DB_WEB_PORT', 3306),
            'name'   => $get('DB_WEB_NAME', 'maytrix_web'),
            'user'   => $get('DB_WEB_USER', 'root'),
            'pass'   => $get('DB_WEB_PASS', ''),
            'sqlite' => BASE_PATH . '/' . ltrim($get('DB_WEB_SQLITE', 'storage/sqlite/maytrix_web.sqlite'), '/'),
        ],
        // ADMIN / AUTH database
        'admin' => [
            'driver' => $driver,
            'host'   => $get('DB_ADMIN_HOST', 'localhost'),
            'port'   => (int) $get('DB_ADMIN_PORT', 3306),
            'name'   => $get('DB_ADMIN_NAME', 'maytrix_admin'),
            'user'   => $get('DB_ADMIN_USER', 'root'),
            'pass'   => $get('DB_ADMIN_PASS', ''),
            'sqlite' => BASE_PATH . '/' . ltrim($get('DB_ADMIN_SQLITE', 'storage/sqlite/maytrix_admin.sqlite'), '/'),
        ],
    ],

    'mail' => [
        'enabled'      => (bool) $get('MAIL_ENABLED', false),
        'host'         => $get('MAIL_HOST', ''),
        'port'         => (int) $get('MAIL_PORT', 465),
        'encryption'   => $get('MAIL_ENCRYPTION', 'ssl'),
        'username'     => $get('MAIL_USERNAME', ''),
        'password'     => $get('MAIL_PASSWORD', ''),
        'from_address' => $get('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
        'from_name'    => $get('MAIL_FROM_NAME', 'Maytrix Education'),
        'admin_notify' => $get('MAIL_ADMIN_NOTIFY', ''),
    ],

    'payment' => [
        'currency' => $get('PAYMENT_CURRENCY', 'INR'),
        'razorpay' => [
            'key_id'     => $get('RAZORPAY_KEY_ID', ''),
            'key_secret' => $get('RAZORPAY_KEY_SECRET', ''),
        ],
        'stripe' => [
            'public' => $get('STRIPE_PUBLIC_KEY', ''),
            'secret' => $get('STRIPE_SECRET_KEY', ''),
        ],
        'paypal' => [
            'client_id' => $get('PAYPAL_CLIENT_ID', ''),
            'secret'    => $get('PAYPAL_SECRET', ''),
        ],
    ],

    'analytics' => [
        'ga_id' => $get('GA_MEASUREMENT_ID', ''),
    ],
];
