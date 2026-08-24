<?php
declare(strict_types=1);

namespace App\Core;

/** CSRF token generation + verification (session-backed). */
final class Csrf
{
    private const KEY = '_csrf_token';

    public static function token(): string
    {
        Session::start();
        if (!Session::has(self::KEY)) {
            Session::set(self::KEY, bin2hex(random_bytes(32)));
        }
        return (string) Session::get(self::KEY);
    }

    public static function field(): string
    {
        return '<input type="hidden" name="_token" value="' . e(self::token()) . '">';
    }

    public static function verify(?string $token): bool
    {
        Session::start();
        $stored = Session::get(self::KEY);
        return is_string($stored) && is_string($token) && hash_equals($stored, $token);
    }

    /** Abort with 419 if the submitted token is invalid. */
    public static function check(?string $token): void
    {
        if (!self::verify($token)) {
            http_response_code(419);
            exit('Invalid or expired security token. Please go back and try again.');
        }
    }
}
