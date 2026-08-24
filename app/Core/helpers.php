<?php
declare(strict_types=1);

use App\Core\Config;

/* -------------------------------------------------------------------------
 * Global helper functions (procedural convenience wrappers).
 * ---------------------------------------------------------------------- */

if (!function_exists('e')) {
    /** HTML-escape a value for safe output. */
    function e($value): string
    {
        return htmlspecialchars((string) ($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('config')) {
    function config(string $key, $default = null)
    {
        return Config::get($key, $default);
    }
}

if (!function_exists('base_url')) {
    /** Absolute site URL (falls back to current host). */
    function base_url(string $path = ''): string
    {
        $base = rtrim((string) config('app.url', ''), '/');
        if ($base === '') {
            $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $base = $scheme . '://' . $host;
        }
        return $base . '/' . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string
    {
        return base_url('assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('admin_asset')) {
    function admin_asset(string $path): string
    {
        return base_url('admin/assets/' . ltrim($path, '/'));
    }
}

if (!function_exists('admin_url')) {
    /** URL within the admin app (mounted at /admin). */
    function admin_url(string $path = ''): string
    {
        return base_url('admin/' . ltrim($path, '/'));
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}

if (!function_exists('old')) {
    /** Retrieve previously-submitted input after a validation redirect. */
    function old(string $key, $default = '')
    {
        $data = $_SESSION['_old'][$key] ?? $default;
        return $data;
    }
}

if (!function_exists('str_slug')) {
    function str_slug(string $text): string
    {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^a-z0-9]+/', '-', $text) ?? '';
        return trim($text, '-');
    }
}

if (!function_exists('now')) {
    function now(): string
    {
        return date('Y-m-d H:i:s');
    }
}

if (!function_exists('array_get')) {
    function array_get(array $array, string $key, $default = null)
    {
        return $array[$key] ?? $default;
    }
}

if (!function_exists('money')) {
    function money($amount, string $currency = 'INR'): string
    {
        $symbols = ['INR' => '₹', 'USD' => '$', 'GBP' => '£', 'EUR' => '€', 'AED' => 'AED '];
        $symbol = $symbols[$currency] ?? ($currency . ' ');
        return $symbol . number_format((float) $amount, 2);
    }
}

if (!function_exists('json_field')) {
    /** Safely decode a JSON column to an array. */
    function json_field($value): array
    {
        if (is_array($value)) {
            return $value;
        }
        if (!is_string($value) || $value === '') {
            return [];
        }
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }
}

if (!function_exists('csp_nonce')) {
    /** Per-request CSP nonce for whitelisted inline scripts. */
    function csp_nonce(): string
    {
        return \App\Core\Security::nonce();
    }
}

if (!function_exists('log_message')) {
    function log_message(string $message, string $channel = 'app'): void
    {
        $file = BASE_PATH . '/storage/logs/' . $channel . '.log';
        @file_put_contents($file, '[' . now() . '] ' . $message . PHP_EOL, FILE_APPEND);
    }
}
