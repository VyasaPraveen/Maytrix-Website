<?php
declare(strict_types=1);

namespace App\Core;

use App\Models\Admin;

/**
 * Admin authentication against the SEPARATE admin/auth database.
 * Uses PHP sessions; passwords hashed with password_hash (bcrypt/argon).
 */
final class Auth
{
    private const SESSION_KEY = '_admin_id';
    private const IDLE_TIMEOUT = 1800;      // 30 min of inactivity
    private const ABSOLUTE_TIMEOUT = 28800; // 8 h max session lifetime

    public static function attempt(string $email, string $password): bool
    {
        $admin = (new Admin())->findBy('email', strtolower(trim($email)));
        if (!$admin || (int) ($admin['is_active'] ?? 0) !== 1) {
            return false;
        }
        if (!password_verify($password, $admin['password_hash'])) {
            return false;
        }
        // Rehash if algorithm/cost changed.
        if (password_needs_rehash($admin['password_hash'], PASSWORD_DEFAULT)) {
            (new Admin())->update($admin['id'], ['password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
        }
        Session::start();
        Session::regenerate();
        Session::set(self::SESSION_KEY, (int) $admin['id']);
        Session::set('_login_time', time());
        Session::set('_last_activity', time());
        (new Admin())->update($admin['id'], ['last_login_at' => now()]);
        return true;
    }

    public static function check(): bool
    {
        Session::start();
        if (!Session::has(self::SESSION_KEY)) {
            return false;
        }
        $now = time();
        $last = (int) Session::get('_last_activity', $now);
        $login = (int) Session::get('_login_time', $now);
        // Idle timeout or absolute session-lifetime cap → force re-login.
        if (($now - $last) > self::IDLE_TIMEOUT || ($now - $login) > self::ABSOLUTE_TIMEOUT) {
            self::logout();
            return false;
        }
        Session::set('_last_activity', $now);
        return true;
    }

    public static function id(): ?int
    {
        Session::start();
        $id = Session::get(self::SESSION_KEY);
        return $id ? (int) $id : null;
    }

    public static function user(): ?array
    {
        $id = self::id();
        if (!$id) {
            return null;
        }
        return (new Admin())->find($id);
    }

    public static function logout(): void
    {
        Session::start();
        Session::forget(self::SESSION_KEY);
        Session::regenerate();
    }

    /** Guard: redirect to login if not authenticated. */
    public static function require(string $loginUrl): void
    {
        if (!self::check()) {
            Flash::error('Please sign in to continue.');
            redirect($loginUrl);
        }
    }

    /** Current admin's role ('admin' | 'editor'), or null when signed out. */
    public static function role(): ?string
    {
        $user = self::user();
        return $user['role'] ?? null;
    }

    public static function isAdmin(): bool
    {
        return self::role() === 'admin';
    }

    /**
     * Guard: only full "admin" role may proceed. Editors are redirected with a
     * message. Assumes authentication was already enforced by require().
     */
    public static function requireRole(string $role, string $redirectUrl): void
    {
        if (self::role() !== $role) {
            Flash::error('You do not have permission to access that area.');
            redirect($redirectUrl);
        }
    }
}
