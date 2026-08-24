<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Brute-force protection for the admin login. Tracks failed attempts per
 * ip|email key in the admin database and locks the key after too many
 * failures. Successful login clears the counter.
 */
final class LoginThrottle
{
    private const MAX_ATTEMPTS = 5;
    private const LOCK_MINUTES = 15;

    private function db(): \PDO
    {
        return Database::admin();
    }

    private function key(string $email): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        return substr($ip . '|' . strtolower(trim($email)), 0, 190);
    }

    private function row(string $identifier): ?array
    {
        $stmt = $this->db()->prepare('SELECT * FROM login_attempts WHERE identifier = ? LIMIT 1');
        $stmt->execute([$identifier]);
        return $stmt->fetch() ?: null;
    }

    /** Seconds remaining on a lock, or 0 if not locked. */
    public function lockedFor(string $email): int
    {
        $row = $this->row($this->key($email));
        if (!$row || empty($row['locked_until'])) {
            return 0;
        }
        $remaining = strtotime((string) $row['locked_until']) - time();
        return $remaining > 0 ? $remaining : 0;
    }

    /** Record a failed attempt; lock the key once the threshold is reached. */
    public function hit(string $email): void
    {
        $id = $this->key($email);
        $row = $this->row($id);
        $now = now();

        if (!$row) {
            $stmt = $this->db()->prepare(
                'INSERT INTO login_attempts (identifier, attempts, updated_at) VALUES (?, 1, ?)'
            );
            $stmt->execute([$id, $now]);
            return;
        }

        $attempts = (int) $row['attempts'] + 1;
        $lockedUntil = null;
        if ($attempts >= self::MAX_ATTEMPTS) {
            $lockedUntil = date('Y-m-d H:i:s', time() + self::LOCK_MINUTES * 60);
            $attempts = 0; // reset the counter once locked
        }
        $stmt = $this->db()->prepare(
            'UPDATE login_attempts SET attempts = ?, locked_until = ?, updated_at = ? WHERE identifier = ?'
        );
        $stmt->execute([$attempts, $lockedUntil, $now, $id]);
    }

    /** Clear the counter after a successful login. */
    public function clear(string $email): void
    {
        $stmt = $this->db()->prepare('DELETE FROM login_attempts WHERE identifier = ?');
        $stmt->execute([$this->key($email)]);
    }

    public static function minutes(): int
    {
        return self::LOCK_MINUTES;
    }
}
