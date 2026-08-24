<?php
declare(strict_types=1);

namespace App\Core;

use PDO;

/**
 * Lightweight active-record-ish base model built on PDO.
 * Each concrete model declares its table, connection ("web"|"admin"),
 * fillable columns and which columns are JSON.
 */
abstract class Model
{
    protected string $table = '';
    protected string $connection = 'web';
    protected string $primaryKey = 'id';
    /** @var string[] Columns allowed for mass-assignment. */
    protected array $fillable = [];
    /** @var string[] Columns stored as JSON text. */
    protected array $json = [];

    public function db(): PDO
    {
        return Database::connection($this->connection);
    }

    /* ---------------- Reads ---------------- */

    public function all(?string $orderBy = null): array
    {
        $sql = "SELECT * FROM {$this->table}";
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        $rows = $this->db()->query($sql)->fetchAll();
        return array_map([$this, 'decodeRow'], $rows);
    }

    public function find($id): ?array
    {
        $stmt = $this->db()->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? $this->decodeRow($row) : null;
    }

    public function findBy(string $column, $value): ?array
    {
        $stmt = $this->db()->prepare("SELECT * FROM {$this->table} WHERE {$column} = ? LIMIT 1");
        $stmt->execute([$value]);
        $row = $stmt->fetch();
        return $row ? $this->decodeRow($row) : null;
    }

    /**
     * Flexible where query.
     * @param array<string,mixed> $conditions column => value (equality)
     */
    public function where(array $conditions, ?string $orderBy = null, ?int $limit = null): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        if ($conditions) {
            $clauses = [];
            foreach ($conditions as $col => $val) {
                $clauses[] = "{$col} = ?";
                $params[] = $val;
            }
            $sql .= ' WHERE ' . implode(' AND ', $clauses);
        }
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        if ($limit !== null) {
            $sql .= " LIMIT " . (int) $limit;
        }
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return array_map([$this, 'decodeRow'], $stmt->fetchAll());
    }

    /**
     * Paginated read. Returns ['rows' => [...], 'pager' => Paginator].
     * @param array<string,mixed> $conditions optional column => value equality filters
     */
    public function paginate(int $page = 1, int $perPage = 20, ?string $orderBy = null, array $conditions = []): array
    {
        $where = '';
        $params = [];
        if ($conditions) {
            $clauses = [];
            foreach ($conditions as $col => $val) {
                $clauses[] = "{$col} = ?";
                $params[] = $val;
            }
            $where = ' WHERE ' . implode(' AND ', $clauses);
        }

        $countStmt = $this->db()->prepare("SELECT COUNT(*) AS c FROM {$this->table}{$where}");
        $countStmt->execute($params);
        $total = (int) ($countStmt->fetch()['c'] ?? 0);

        $pager = new Paginator($total, $page, $perPage);

        $sql = "SELECT * FROM {$this->table}{$where}";
        if ($orderBy) {
            $sql .= " ORDER BY {$orderBy}";
        }
        $sql .= ' LIMIT ' . $pager->perPage . ' OFFSET ' . $pager->offset;
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);

        return [
            'rows'  => array_map([$this, 'decodeRow'], $stmt->fetchAll()),
            'pager' => $pager,
        ];
    }

    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) AS c FROM {$this->table}";
        $params = [];
        if ($conditions) {
            $clauses = [];
            foreach ($conditions as $col => $val) {
                $clauses[] = "{$col} = ?";
                $params[] = $val;
            }
            $sql .= ' WHERE ' . implode(' AND ', $clauses);
        }
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return (int) ($stmt->fetch()['c'] ?? 0);
    }

    /* ---------------- Writes ---------------- */

    public function create(array $data): int
    {
        $data = $this->onlyFillable($data);
        $data = $this->encodeRow($data);
        if (in_array('created_at', $this->fillable, true) && !isset($data['created_at'])) {
            $data['created_at'] = now();
        }
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');
        $sql = "INSERT INTO {$this->table} (" . implode(',', $columns) . ') VALUES (' . implode(',', $placeholders) . ')';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute(array_values($data));
        return (int) $this->db()->lastInsertId();
    }

    public function update($id, array $data): bool
    {
        $data = $this->onlyFillable($data);
        $data = $this->encodeRow($data);
        if (empty($data)) {
            return false;
        }
        $set = implode(',', array_map(fn ($c) => "{$c} = ?", array_keys($data)));
        $sql = "UPDATE {$this->table} SET {$set} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db()->prepare($sql);
        $values = array_values($data);
        $values[] = $id;
        return $stmt->execute($values);
    }

    public function delete($id): bool
    {
        $stmt = $this->db()->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }

    /* ---------------- JSON helpers ---------------- */

    protected function onlyFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }
        return array_intersect_key($data, array_flip($this->fillable));
    }

    protected function encodeRow(array $data): array
    {
        foreach ($this->json as $col) {
            if (array_key_exists($col, $data) && !is_string($data[$col])) {
                $data[$col] = json_encode($data[$col] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
        }
        return $data;
    }

    protected function decodeRow(array $row): array
    {
        foreach ($this->json as $col) {
            if (array_key_exists($col, $row)) {
                $row[$col] = json_field($row[$col]);
            }
        }
        return $row;
    }
}
