<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use App\Core\Paginator;

final class Enrolment extends Model
{
    protected string $table = 'enrolments';
    protected array $fillable = [
        'batch_id', 'student_id', 'status', 'payment_status', 'payment_ref',
        'amount', 'currency', 'notes', 'enrolled_at', 'confirmed_at', 'created_at',
    ];

    /** Enrolments for a batch joined with student details. */
    public function forBatch(int $batchId): array
    {
        $stmt = $this->db()->prepare(
            "SELECT e.*, st.name AS student_name, st.email AS student_email,
                    st.country AS student_country
             FROM enrolments e
             JOIN students st ON st.id = e.student_id
             WHERE e.batch_id = ?
             ORDER BY e.created_at DESC"
        );
        $stmt->execute([$batchId]);
        return $stmt->fetchAll();
    }

    /**
     * Paginated enrolment list with student + batch labels, optional status filter.
     * Returns ['rows' => [...], 'pager' => Paginator].
     */
    public function paginateWithDetails(int $page = 1, int $perPage = 20, ?string $status = null): array
    {
        $where = '';
        $params = [];
        if ($status !== null && $status !== '') {
            $where = ' WHERE e.status = ?';
            $params[] = $status;
        }

        $countStmt = $this->db()->prepare("SELECT COUNT(*) AS c FROM enrolments e{$where}");
        $countStmt->execute($params);
        $total = (int) ($countStmt->fetch()['c'] ?? 0);

        $pager = new Paginator($total, $page, $perPage);

        $sql = "SELECT e.*, st.name AS student_name, st.email AS student_email,
                       b.name AS batch_name
                FROM enrolments e
                JOIN students st ON st.id = e.student_id
                JOIN batches  b  ON b.id = e.batch_id
                {$where}
                ORDER BY e.id DESC
                LIMIT " . $pager->perPage . ' OFFSET ' . $pager->offset;
        $stmt = $this->db()->prepare($sql);
        $stmt->execute($params);

        return ['rows' => $stmt->fetchAll(), 'pager' => $pager];
    }

    public function recentWithDetails(int $limit = 50): array
    {
        $sql = "SELECT e.*, st.name AS student_name, st.email AS student_email,
                       b.name AS batch_name
                FROM enrolments e
                JOIN students st ON st.id = e.student_id
                JOIN batches  b  ON b.id = e.batch_id
                ORDER BY e.id DESC
                LIMIT " . (int) $limit;
        return $this->db()->query($sql)->fetchAll();
    }
}
