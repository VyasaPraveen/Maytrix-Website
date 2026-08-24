<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

final class Batch extends Model
{
    protected string $table = 'batches';
    protected array $fillable = [
        'course_id', 'mode_id', 'name', 'tutor_name', 'start_date', 'end_date',
        'schedule_text', 'duration_text', 'timezone', 'max_seats', 'price', 'currency',
        'mode_detail', 'zoom_link', 'meeting_notes', 'status', 'is_published',
        'created_at', 'updated_at',
    ];

    /**
     * Number of confirmed enrolments for a batch (drives seat count).
     * Seats consumed = enrolments with status 'confirmed'.
     */
    public function confirmedCount(int $batchId): int
    {
        $stmt = $this->db()->prepare(
            "SELECT COUNT(*) c FROM enrolments WHERE batch_id = ? AND status = 'confirmed'"
        );
        $stmt->execute([$batchId]);
        return (int) ($stmt->fetch()['c'] ?? 0);
    }

    public function availableSeats(array $batch): int
    {
        $taken = $this->confirmedCount((int) $batch['id']);
        return max(0, (int) $batch['max_seats'] - $taken);
    }

    /**
     * Published, open batches with course/curriculum/subject/mode labels and
     * a computed available-seats field. This is what the public site lists.
     */
    public function publishedWithDetails(array $conditions = []): array
    {
        $sql = "SELECT b.*,
                       co.title AS course_title, co.class_type,
                       c.name AS curriculum_name, c.code AS curriculum_code,
                       s.name AS subject_name, s.code AS subject_code,
                       m.name AS mode_name, m.code AS mode_code,
                       (SELECT COUNT(*) FROM enrolments e
                          WHERE e.batch_id = b.id AND e.status = 'confirmed') AS taken_seats
                FROM batches b
                JOIN courses   co ON co.id = b.course_id
                JOIN curricula c  ON c.id = co.curriculum_id
                JOIN subjects  s  ON s.id = co.subject_id
                LEFT JOIN modes m ON m.id = b.mode_id
                WHERE b.is_published = 1";
        $params = [];
        foreach ($conditions as $col => $val) {
            $sql .= " AND b.{$col} = ?";
            $params[] = $val;
        }
        $sql .= ' ORDER BY b.start_date ASC, b.id ASC';
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();
        foreach ($rows as &$r) {
            $r['available_seats'] = max(0, (int) $r['max_seats'] - (int) $r['taken_seats']);
        }
        return $rows;
    }

    /** All batches (admin view) with labels + seat data. */
    public function allWithDetails(): array
    {
        $sql = "SELECT b.*, co.title AS course_title, co.class_type,
                       m.name AS mode_name,
                       (SELECT COUNT(*) FROM enrolments e
                          WHERE e.batch_id = b.id AND e.status = 'confirmed') AS taken_seats
                FROM batches b
                JOIN courses co ON co.id = b.course_id
                LEFT JOIN modes m ON m.id = b.mode_id
                ORDER BY b.start_date DESC, b.id DESC";
        $rows = $this->db()->query($sql)->fetchAll();
        foreach ($rows as &$r) {
            $r['available_seats'] = max(0, (int) $r['max_seats'] - (int) $r['taken_seats']);
        }
        return $rows;
    }

    /** Single batch (published or not) with labels + live seat computation. */
    public function findWithDetails(int $id): ?array
    {
        $sql = "SELECT b.*,
                       co.title AS course_title, co.class_type,
                       c.name AS curriculum_name, c.code AS curriculum_code,
                       s.name AS subject_name, s.code AS subject_code,
                       m.name AS mode_name, m.code AS mode_code,
                       (SELECT COUNT(*) FROM enrolments e
                          WHERE e.batch_id = b.id AND e.status = 'confirmed') AS taken_seats
                FROM batches b
                JOIN courses   co ON co.id = b.course_id
                JOIN curricula c  ON c.id = co.curriculum_id
                JOIN subjects  s  ON s.id = co.subject_id
                LEFT JOIN modes m ON m.id = b.mode_id
                WHERE b.id = ? LIMIT 1";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            return null;
        }
        $row['available_seats'] = max(0, (int) $row['max_seats'] - (int) $row['taken_seats']);
        return $row;
    }
}
