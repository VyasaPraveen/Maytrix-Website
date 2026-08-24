<?php
/**
 * Compiles the schema.php DSL into CREATE/DROP TABLE statements for MySQL or
 * SQLite. Shared by the CLI migrator and the web installer.
 */

declare(strict_types=1);

final class SchemaCompiler
{
    public function __construct(private string $driver) {}

    public function createTable(string $table, array $def): string
    {
        $lines = [];
        foreach ($def['columns'] as $col) {
            $lines[] = '  ' . $this->column($col);
        }
        $body = implode(",\n", $lines);
        if ($this->driver === 'mysql') {
            return "CREATE TABLE IF NOT EXISTS `{$table}` (\n{$body}\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        }
        return "CREATE TABLE IF NOT EXISTS \"{$table}\" (\n{$body}\n);";
    }

    public function dropTable(string $table): string
    {
        return $this->driver === 'mysql'
            ? "DROP TABLE IF EXISTS `{$table}`;"
            : "DROP TABLE IF EXISTS \"{$table}\";";
    }

    /**
     * CREATE INDEX statements for a table's declared 'indexes'.
     * @return string[]
     */
    public function indexStatements(string $table, array $def): array
    {
        $out = [];
        foreach ($def['indexes'] ?? [] as $cols) {
            $cols = (array) $cols;
            $idxName = 'idx_' . $table . '_' . implode('_', $cols);
            if ($this->driver === 'mysql') {
                $colList = implode(', ', array_map(fn ($c) => "`{$c}`", $cols));
                $out[] = "CREATE INDEX `{$idxName}` ON `{$table}` ({$colList});";
            } else {
                $colList = implode(', ', array_map(fn ($c) => "\"{$c}\"", $cols));
                $out[] = "CREATE INDEX IF NOT EXISTS \"{$idxName}\" ON \"{$table}\" ({$colList});";
            }
        }
        return $out;
    }

    private function column(string $dsl): string
    {
        $parts = preg_split('/\s+/', trim($dsl));
        $name = array_shift($parts);
        $type = array_shift($parts);
        $mods = $parts;
        $q = $this->driver === 'mysql' ? "`{$name}`" : "\"{$name}\"";

        if ($type === 'pk') {
            return $this->driver === 'mysql'
                ? "{$q} INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY"
                : "{$q} INTEGER PRIMARY KEY AUTOINCREMENT";
        }

        $def = "{$q} " . $this->mapType($type);
        if (in_array('notnull', $mods, true)) {
            $def .= ' NOT NULL';
        } elseif (in_array('null', $mods, true)) {
            $def .= ' NULL';
        }
        foreach ($mods as $mod) {
            if (str_starts_with($mod, 'default:')) {
                $def .= ' DEFAULT ' . $this->defaultValue(substr($mod, 8));
            }
        }
        if (in_array('unique', $mods, true)) {
            $def .= ' UNIQUE';
        }
        return $def;
    }

    private function mapType(string $type): string
    {
        if (preg_match('/^string\((\d+)\)$/', $type, $m)) {
            return $this->driver === 'mysql' ? "VARCHAR({$m[1]})" : 'TEXT';
        }
        if (preg_match('/^decimal\((\d+),(\d+)\)$/', $type, $m)) {
            return $this->driver === 'mysql' ? "DECIMAL({$m[1]},{$m[2]})" : 'NUMERIC';
        }
        return match ($type) {
            'int'      => $this->driver === 'mysql' ? 'INT' : 'INTEGER',
            'bigint'   => $this->driver === 'mysql' ? 'BIGINT' : 'INTEGER',
            'bool'     => $this->driver === 'mysql' ? 'TINYINT(1)' : 'INTEGER',
            'text'     => 'TEXT',
            'longtext' => $this->driver === 'mysql' ? 'LONGTEXT' : 'TEXT',
            'json'     => $this->driver === 'mysql' ? 'LONGTEXT' : 'TEXT',
            'date'     => $this->driver === 'mysql' ? 'DATE' : 'TEXT',
            'datetime' => $this->driver === 'mysql' ? 'DATETIME' : 'TEXT',
            default    => 'TEXT',
        };
    }

    private function defaultValue(string $val): string
    {
        if (is_numeric($val)) {
            return $val;
        }
        if (in_array(strtoupper($val), ['CURRENT_TIMESTAMP', 'NULL'], true)) {
            return strtoupper($val);
        }
        return "'" . str_replace("'", "''", $val) . "'";
    }

    /** Group schema tables by connection ('web'|'admin'). */
    public static function byConnection(array $schema): array
    {
        $out = ['web' => [], 'admin' => []];
        foreach ($schema as $table => $def) {
            $out[$def['connection']][$table] = $def;
        }
        return $out;
    }
}
