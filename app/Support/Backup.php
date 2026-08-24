<?php
declare(strict_types=1);

namespace App\Support;

use App\Core\Database;

/**
 * Generates a self-contained SQL dump (structure + data) for one of the two
 * databases ("web" | "admin"). Driver-aware: emits MySQL or SQLite compatible
 * SQL matching the live connection, so the file can be re-imported through
 * phpMyAdmin (production) or the sqlite CLI (local).
 *
 * Reuses the project's SchemaCompiler for CREATE TABLE so structure always
 * matches schema.php — the single source of truth.
 */
final class Backup
{
    /** Tables that live in each database, in dependency-friendly order. */
    public static function tablesFor(string $conn): array
    {
        require_once BASE_PATH . '/database/SchemaCompiler.php';
        $schema = require BASE_PATH . '/database/schema.php';
        $byConn = \SchemaCompiler::byConnection($schema);
        return array_keys($byConn[$conn] ?? []);
    }

    /** Lightweight per-table row counts + grand total for the given connection. */
    public static function summary(string $conn): array
    {
        $pdo = Database::connection($conn);
        $counts = [];
        $total = 0;
        foreach (self::tablesFor($conn) as $table) {
            try {
                $n = (int) ($pdo->query('SELECT COUNT(*) c FROM ' . self::quoteIdent($table))->fetch()['c'] ?? 0);
            } catch (\Throwable $e) {
                $n = 0;
            }
            $counts[$table] = $n;
            $total += $n;
        }
        return ['tables' => $counts, 'total_rows' => $total, 'table_count' => count($counts)];
    }

    /**
     * Build the full SQL dump for a connection as a string.
     */
    public static function dump(string $conn): string
    {
        require_once BASE_PATH . '/database/SchemaCompiler.php';
        $schema  = require BASE_PATH . '/database/schema.php';
        $byConn  = \SchemaCompiler::byConnection($schema);
        $tables  = $byConn[$conn] ?? [];
        $driver  = Database::driver();
        $pdo     = Database::connection($conn);
        $compiler = new \SchemaCompiler($driver);

        $out  = "-- Maytrix Education — {$conn} database backup\n";
        $out .= '-- Driver: ' . $driver . "\n";
        $out .= '-- Generated: ' . date('Y-m-d H:i:s') . "\n";
        $out .= "-- NOTE: contains live data. Store securely.\n\n";

        if ($driver === 'mysql') {
            $out .= "SET FOREIGN_KEY_CHECKS=0;\n";
            $out .= "SET NAMES utf8mb4;\n\n";
        } else {
            $out .= "PRAGMA foreign_keys=OFF;\nBEGIN TRANSACTION;\n\n";
        }

        foreach ($tables as $table => $def) {
            $out .= "-- ----- Table: {$table} -----\n";
            $out .= $compiler->dropTable($table) . "\n";
            $out .= $compiler->createTable($table, $def) . "\n";
            foreach ($compiler->indexStatements($table, $def) as $idx) {
                $out .= $idx . "\n";
            }
            $out .= "\n";
            $out .= self::dumpRows($pdo, $table, $driver);
            $out .= "\n";
        }

        if ($driver === 'mysql') {
            $out .= "SET FOREIGN_KEY_CHECKS=1;\n";
        } else {
            $out .= "COMMIT;\nPRAGMA foreign_keys=ON;\n";
        }

        return $out;
    }

    /** INSERT statements for every row in a table (chunked). */
    private static function dumpRows(\PDO $pdo, string $table, string $driver): string
    {
        $rows = $pdo->query('SELECT * FROM ' . self::quoteIdent($table))->fetchAll(\PDO::FETCH_ASSOC);
        if (!$rows) {
            return "-- (no rows)\n";
        }
        $cols = array_keys($rows[0]);
        $colList = implode(', ', array_map([self::class, 'quoteIdent'], $cols));

        $sql = '';
        $chunkSize = 100;
        foreach (array_chunk($rows, $chunkSize) as $chunk) {
            $values = [];
            foreach ($chunk as $row) {
                $cells = [];
                foreach ($cols as $c) {
                    $cells[] = self::quoteValue($pdo, $row[$c]);
                }
                $values[] = '(' . implode(', ', $cells) . ')';
            }
            $sql .= 'INSERT INTO ' . self::quoteIdent($table) . " ({$colList}) VALUES\n"
                  . implode(",\n", $values) . ";\n";
        }
        return $sql;
    }

    private static function quoteIdent(string $ident): string
    {
        // Strip any stray backtick/quote to keep identifiers safe.
        $ident = str_replace(['`', '"', "\0"], '', $ident);
        return Database::driver() === 'mysql' ? "`{$ident}`" : "\"{$ident}\"";
    }

    private static function quoteValue(\PDO $pdo, $val): string
    {
        if ($val === null) {
            return 'NULL';
        }
        if (is_int($val) || is_float($val)) {
            return (string) $val;
        }
        // PDO::quote handles driver-correct escaping.
        $quoted = $pdo->quote((string) $val);
        return $quoted === false ? "'" . str_replace("'", "''", (string) $val) . "'" : $quoted;
    }
}
