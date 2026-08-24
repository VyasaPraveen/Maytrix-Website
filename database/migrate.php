<?php
/**
 * Database migrator — compiles the DSL in schema.php into CREATE TABLE
 * statements for the configured driver and runs them against BOTH databases.
 *
 * CLI usage:
 *   php database/migrate.php            # create tables in configured DB(s)
 *   php database/migrate.php --fresh    # drop & recreate all tables
 *   php database/migrate.php --seed     # migrate then run seed.php
 *   php database/migrate.php --sql      # export MySQL .sql files (no DB needed)
 *
 * Can also be run once via the browser installer (public_html/admin/install.php).
 */

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));
require BASE_PATH . '/app/bootstrap.php';
require BASE_PATH . '/database/SchemaCompiler.php';

use App\Core\Database;

/* ----------------------------- Runner ----------------------------- */

$schema = require BASE_PATH . '/database/schema.php';
$args = array_slice($argv ?? [], 1);
$fresh = in_array('--fresh', $args, true);
$doSeed = in_array('--seed', $args, true);
$sqlOnly = in_array('--sql', $args, true);

$byConn = SchemaCompiler::byConnection($schema);

/* --- SQL export mode: write MySQL .sql files for phpMyAdmin --- */
if ($sqlOnly) {
    $mysql = new SchemaCompiler('mysql');
    foreach (['web' => 'maytrix_web', 'admin' => 'maytrix_admin'] as $conn => $dbName) {
        $out = "-- Maytrix Education — {$dbName} schema (MySQL)\n";
        $out .= "-- Generated from database/schema.php. Import via phpMyAdmin.\n\n";
        $out .= "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS = 0;\n\n";
        foreach ($byConn[$conn] as $table => $def) {
            $out .= $mysql->createTable($table, $def) . "\n";
            foreach ($mysql->indexStatements($table, $def) as $idx) {
                $out .= $idx . "\n";
            }
            $out .= "\n";
        }
        $out .= "SET FOREIGN_KEY_CHECKS = 1;\n";
        $file = BASE_PATH . "/database/schema_{$conn}.sql";
        file_put_contents($file, $out);
        echo "Wrote {$file}\n";
    }
    exit(0);
}

/* --- Live migration mode --- */
$driver = Database::driver();
$compiler = new SchemaCompiler($driver);

foreach (['web', 'admin'] as $conn) {
    $pdo = Database::connection($conn);
    echo "Migrating '{$conn}' database (driver: {$driver})...\n";

    if ($fresh) {
        foreach (array_reverse(array_keys($byConn[$conn])) as $table) {
            $pdo->exec($compiler->dropTable($table));
        }
    }
    foreach ($byConn[$conn] as $table => $def) {
        $pdo->exec($compiler->createTable($table, $def));
        foreach ($compiler->indexStatements($table, $def) as $idx) {
            try {
                $pdo->exec($idx);
            } catch (\PDOException $e) {
                // Index already exists (non-fresh re-run) — safe to ignore.
            }
        }
        echo "  ✓ {$table}\n";
    }
}

echo "Migration complete.\n";

if ($doSeed) {
    echo "Seeding...\n";
    require BASE_PATH . '/database/seed.php';
}
