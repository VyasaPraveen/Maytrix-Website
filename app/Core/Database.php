<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

/**
 * Driver-aware PDO connection manager holding TWO separate connections:
 *   - "web"   → content / website database (maytrix_web)
 *   - "admin" → admin / auth database      (maytrix_admin)
 *
 * Supports MySQL (Hostinger / production) and SQLite (local development),
 * selected by DB_DRIVER in .env. The application code is identical for both.
 */
final class Database
{
    private static array $config = [];
    /** @var array<string,PDO> */
    private static array $connections = [];

    public static function boot(array $dbConfig): void
    {
        self::$config = $dbConfig;
    }

    /** Get the content/website connection. */
    public static function web(): PDO
    {
        return self::connection('web');
    }

    /** Get the admin/auth connection. */
    public static function admin(): PDO
    {
        return self::connection('admin');
    }

    public static function connection(string $name): PDO
    {
        if (isset(self::$connections[$name])) {
            return self::$connections[$name];
        }
        if (!isset(self::$config[$name])) {
            throw new \RuntimeException("Unknown database connection: {$name}");
        }

        $cfg = self::$config[$name];
        $driver = $cfg['driver'] ?? 'mysql';

        try {
            if ($driver === 'sqlite') {
                $path = $cfg['sqlite'];
                $dir = dirname($path);
                if (!is_dir($dir)) {
                    @mkdir($dir, 0775, true);
                }
                $pdo = new PDO('sqlite:' . $path);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $pdo->exec('PRAGMA foreign_keys = ON');
            } else {
                $dsn = sprintf(
                    'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                    $cfg['host'],
                    $cfg['port'] ?? 3306,
                    $cfg['name']
                );
                $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            }
        } catch (PDOException $e) {
            // Never leak credentials in the message.
            error_log("DB connect failed [{$name}]: " . $e->getMessage());
            throw new \RuntimeException("Database connection failed ({$name}).", 0, $e);
        }

        self::$connections[$name] = $pdo;
        return $pdo;
    }

    public static function driver(): string
    {
        return self::$config['driver'] ?? 'mysql';
    }
}
