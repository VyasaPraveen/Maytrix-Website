<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Static access to the loaded configuration array via dot-notation.
 * Usage: Config::get('mail.host'), Config::get('app.debug', false)
 */
final class Config
{
    private static array $items = [];

    public static function boot(array $config): void
    {
        self::$items = $config;
    }

    public static function all(): array
    {
        return self::$items;
    }

    public static function get(string $key, $default = null)
    {
        $segments = explode('.', $key);
        $value = self::$items;
        foreach ($segments as $segment) {
            if (is_array($value) && array_key_exists($segment, $value)) {
                $value = $value[$segment];
            } else {
                return $default;
            }
        }
        return $value;
    }
}
