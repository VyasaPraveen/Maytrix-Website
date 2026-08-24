<?php
declare(strict_types=1);

namespace App\Core;

/** One-request flash messages + old-input persistence. */
final class Flash
{
    public static function set(string $type, string $message): void
    {
        Session::start();
        $_SESSION['_flash'][$type] = $message;
    }

    public static function success(string $message): void
    {
        self::set('success', $message);
    }

    public static function error(string $message): void
    {
        self::set('error', $message);
    }

    /** Pull and clear all flash messages. */
    public static function pull(): array
    {
        Session::start();
        $flash = $_SESSION['_flash'] ?? [];
        unset($_SESSION['_flash']);
        return $flash;
    }

    /** Persist submitted input + validation errors for the next request. */
    public static function withInput(array $input, array $errors = []): void
    {
        Session::start();
        $_SESSION['_old'] = $input;
        $_SESSION['_errors'] = $errors;
    }

    /** Read old input WITHOUT clearing (call before errors()). */
    public static function oldInput(): array
    {
        Session::start();
        return $_SESSION['_old'] ?? [];
    }

    public static function errors(): array
    {
        Session::start();
        $errors = $_SESSION['_errors'] ?? [];
        unset($_SESSION['_errors'], $_SESSION['_old']);
        return $errors;
    }
}
