<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Paginator;

final class Booking extends Model
{
    protected string $table = 'bookings';
    protected array $fillable = [
        'student_id', 'name', 'email', 'phone', 'country', 'timezone',
        'curriculum_id', 'subject_id', 'class_type', 'mode_id', 'preferred_contact',
        'message', 'status', 'zoom_link', 'scheduled_at', 'admin_notes', 'created_at',
    ];

    public function recentWithLabels(int $limit = 100): array
    {
        $sql = "SELECT b.*, c.name AS curriculum_name, s.name AS subject_name, m.name AS mode_name,
                       st.country AS student_country
                FROM bookings b
                LEFT JOIN curricula c ON c.id = b.curriculum_id
                LEFT JOIN subjects  s ON s.id = b.subject_id
                LEFT JOIN modes     m ON m.id = b.mode_id
                LEFT JOIN students  st ON st.id = b.student_id
                ORDER BY b.id DESC
                LIMIT " . (int) $limit;
        return $this->db()->query($sql)->fetchAll();
    }

    /**
     * Paginated bookings list with curriculum/subject/mode + student labels.
     * Returns ['rows' => [...], 'pager' => Paginator].
     */
    public function paginateWithLabels(int $page = 1, int $perPage = 20): array
    {
        $total = (int) ($this->db()->query('SELECT COUNT(*) AS c FROM bookings')->fetch()['c'] ?? 0);
        $pager = new Paginator($total, $page, $perPage);

        $sql = "SELECT b.*, c.name AS curriculum_name, s.name AS subject_name, m.name AS mode_name,
                       st.country AS student_country
                FROM bookings b
                LEFT JOIN curricula c ON c.id = b.curriculum_id
                LEFT JOIN subjects  s ON s.id = b.subject_id
                LEFT JOIN modes     m ON m.id = b.mode_id
                LEFT JOIN students  st ON st.id = b.student_id
                ORDER BY b.id DESC
                LIMIT " . $pager->perPage . ' OFFSET ' . $pager->offset;
        $rows = $this->db()->query($sql)->fetchAll();

        return ['rows' => $rows, 'pager' => $pager];
    }

    public function findWithLabels(int $id): ?array
    {
        $sql = "SELECT b.*, c.name AS curriculum_name, s.name AS subject_name, m.name AS mode_name,
                       st.phone AS student_phone, st.timezone AS student_timezone, st.country AS student_country
                FROM bookings b
                LEFT JOIN curricula c ON c.id = b.curriculum_id
                LEFT JOIN subjects  s ON s.id = b.subject_id
                LEFT JOIN modes     m ON m.id = b.mode_id
                LEFT JOIN students  st ON st.id = b.student_id
                WHERE b.id = ? LIMIT 1";
        $stmt = $this->db()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }
}
