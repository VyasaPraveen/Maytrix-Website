<?php
/**
 * One-time web installer for Hostinger (when SSH/CLI is unavailable).
 * Creates all tables in BOTH databases and seeds initial content.
 *
 * SECURITY:
 *   - Refuses to run if the admin table is already populated (unless ?force=1).
 *   - DELETE THIS FILE immediately after a successful install.
 *
 * Usage: browse to https://yourdomain.com/install.php
 */

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/app/bootstrap.php';
require BASE_PATH . '/database/SchemaCompiler.php';

use App\Core\Database;

header('Content-Type: text/html; charset=UTF-8');

$driver = Database::driver();
$schema = require BASE_PATH . '/database/schema.php';
$byConn = SchemaCompiler::byConnection($schema);
$compiler = new SchemaCompiler($driver);
$force = isset($_GET['force']);

echo '<!doctype html><html><head><meta charset="utf-8"><title>Maytrix Installer</title>';
echo '<style>body{font-family:system-ui,sans-serif;max-width:720px;margin:40px auto;padding:0 20px;color:#10182B;line-height:1.6;}'
   . 'code{background:#EFE9DA;padding:2px 6px;border-radius:3px;}.ok{color:#164F4C;}.err{color:#B3402F;}'
   . '.box{background:#F6F3EA;border:1px solid #DED6C2;border-radius:8px;padding:20px 24px;margin:18px 0;}'
   . 'h1{font-size:24px;}</style></head><body>';
echo '<h1>Maytrix Education — Installer</h1>';

try {
    // Guard: already installed?
    $installed = false;
    try {
        $c = Database::admin()->query("SELECT COUNT(*) c FROM admins")->fetch();
        $installed = ((int) ($c['c'] ?? 0)) > 0;
    } catch (\Throwable $e) {
        $installed = false; // table doesn't exist yet
    }

    if ($installed && !$force) {
        echo '<div class="box"><p class="err"><strong>Already installed.</strong> The admin table already contains users.</p>';
        echo '<p>If you really want to wipe and reinstall (this deletes all data), add <code>?force=1</code> to the URL. Otherwise, '
           . '<strong>delete this file now</strong> and go to <a href="admin/">the dashboard</a>.</p></div>';
        echo '</body></html>';
        exit;
    }

    echo '<div class="box"><p>Driver: <code>' . e($driver) . '</code></p><p>Creating tables…</p><ul>';
    foreach (['web', 'admin'] as $conn) {
        $pdo = Database::connection($conn);
        if ($force) {
            foreach (array_reverse(array_keys($byConn[$conn])) as $table) {
                $pdo->exec($compiler->dropTable($table));
            }
        }
        foreach ($byConn[$conn] as $table => $def) {
            $pdo->exec($compiler->createTable($table, $def));
            foreach ($compiler->indexStatements($table, $def) as $idx) {
                try { $pdo->exec($idx); } catch (\PDOException $e) { /* exists */ }
            }
            echo '<li class="ok">✓ ' . e($conn) . ' · ' . e($table) . '</li>';
        }
    }
    echo '</ul></div>';

    // Seed.
    echo '<div class="box"><p>Seeding initial content…</p><pre style="white-space:pre-wrap;">';
    ob_start();
    require BASE_PATH . '/database/seed.php';
    echo e(ob_get_clean());
    echo '</pre></div>';

    echo '<div class="box"><h2 class="ok">Installation complete 🎉</h2>';
    echo '<p><strong>Now do this immediately:</strong></p><ol>';
    echo '<li><strong>Delete <code>public_html/install.php</code></strong> (this file).</li>';
    echo '<li>Sign in at <a href="admin/">/admin</a> with the seeded credentials shown above.</li>';
    echo '<li><strong>Change the admin password</strong> under Admin Users.</li>';
    echo '</ol></div>';
} catch (\Throwable $e) {
    echo '<div class="box"><p class="err"><strong>Install failed:</strong> ' . e($e->getMessage()) . '</p>';
    echo '<p>Check your database credentials in <code>.env</code> and that both databases exist.</p></div>';
}
echo '</body></html>';
