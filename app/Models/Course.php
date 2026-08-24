<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Course extends Model
{
    protected string $table = 'courses';
    protected array $fillable = [
        'curriculum_id', 'subject_id', 'class_type', 'title', 'slug', 'summary',
        'description', 'level', 'default_price', 'currency', 'is_active', 'sort_order',
        'created_at', 'updated_at',
    ];

    /** Courses with curriculum + subject labels joined. */
    public function withLabels(array $conditions = []): array
    {
        $sql = "SELECT co.*, c.name AS curriculum_name, s.name AS subject_name
                FROM courses co
                JOIN curricula c ON c.id = co.curriculum_id
                JOIN subjects  s ON s.id = co.subject_id";
        $params = [];
        if ($conditions) {
            $clauses = [];
            foreach ($conditions as $col => $val) {
                $clauses[] = "co.{$col} = ?";
                $params[] = $val;
            }
            $sql .= ' WHERE ' . implode(' AND ', $clauses);
        }
        $sql .= ' ORDER BY co.sort_order ASC, co.id DESC';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
