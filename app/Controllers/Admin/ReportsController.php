<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Database;
use App\Models\Batch;

/**
 * Business reports — read-only analytics aggregated from the content database:
 * enrolment/booking pipelines, revenue, and demand by curriculum & country.
 * All queries are portable across MySQL (production) and SQLite (local).
 */
final class ReportsController extends AdminController
{
    public function index(Request $request): void
    {
        $db = Database::web();

        // ---- Headline metrics ----
        $students        = $this->scalar($db, 'SELECT COUNT(*) c FROM students');
        $confirmedEnrol  = $this->scalar($db, "SELECT COUNT(*) c FROM enrolments WHERE status='confirmed'");
        $pendingEnrol    = $this->scalar($db, "SELECT COUNT(*) c FROM enrolments WHERE status='pending'");
        $openBookings    = $this->scalar($db, "SELECT COUNT(*) c FROM bookings WHERE status IN ('new','contacted')");

        // ---- Revenue (paid enrolments), grouped by currency ----
        $revenue = $db->query(
            "SELECT currency, COUNT(*) cnt, COALESCE(SUM(amount),0) total
             FROM enrolments WHERE payment_status='paid'
             GROUP BY currency ORDER BY total DESC"
        )->fetchAll();
        $paidCount   = (int) $this->scalar($db, "SELECT COUNT(*) c FROM enrolments WHERE payment_status='paid'");
        $unpaidCount = (int) $this->scalar($db, "SELECT COUNT(*) c FROM enrolments WHERE payment_status<>'paid'");

        // ---- Enrolments by status ----
        $enrolByStatus = $this->groupCounts($db,
            "SELECT status AS k, COUNT(*) AS cnt FROM enrolments GROUP BY status ORDER BY cnt DESC");

        // ---- Bookings by status ----
        $bookingByStatus = $this->groupCounts($db,
            "SELECT status AS k, COUNT(*) AS cnt FROM bookings GROUP BY status ORDER BY cnt DESC");

        // ---- Demand by curriculum (confirmed + pending enrolments) ----
        $byCurriculum = $this->groupCounts($db,
            "SELECT cur.name AS k, COUNT(*) AS cnt
             FROM enrolments e
             JOIN batches b   ON b.id = e.batch_id
             JOIN courses co  ON co.id = b.course_id
             JOIN curricula cur ON cur.id = co.curriculum_id
             GROUP BY cur.id, cur.name ORDER BY cnt DESC");

        // ---- Students by country ----
        $byCountryRaw = $db->query(
            "SELECT country AS k, COUNT(*) AS cnt FROM students GROUP BY country ORDER BY cnt DESC"
        )->fetchAll();
        $byCountry = [];
        foreach ($byCountryRaw as $r) {
            $label = trim((string) ($r['k'] ?? '')) !== '' ? $r['k'] : 'Unknown';
            $byCountry[] = ['k' => $label, 'cnt' => (int) $r['cnt']];
        }

        // ---- Batch fill rates (top by demand) ----
        $batches = (new Batch())->allWithDetails();
        usort($batches, function ($a, $b) {
            $fa = (int) $a['max_seats'] > 0 ? ((int)$a['max_seats'] - (int)$a['available_seats']) / (int)$a['max_seats'] : 0;
            $fb = (int) $b['max_seats'] > 0 ? ((int)$b['max_seats'] - (int)$b['available_seats']) / (int)$b['max_seats'] : 0;
            return $fb <=> $fa;
        });
        $batchFill = array_slice($batches, 0, 8);

        $this->renderAdmin('admin/reports/index', [
            'pageTitle'  => 'Reports',
            'activeMenu' => 'reports',
            'metrics' => [
                'students'       => (int) $students,
                'confirmedEnrol' => (int) $confirmedEnrol,
                'pendingEnrol'   => (int) $pendingEnrol,
                'openBookings'   => (int) $openBookings,
                'paidCount'      => $paidCount,
                'unpaidCount'    => $unpaidCount,
            ],
            'revenue'         => $revenue,
            'enrolByStatus'   => $enrolByStatus,
            'bookingByStatus' => $bookingByStatus,
            'byCurriculum'    => $byCurriculum,
            'byCountry'       => $byCountry,
            'batchFill'       => $batchFill,
        ]);
    }

    private function scalar(\PDO $db, string $sql)
    {
        return $db->query($sql)->fetch()['c'] ?? 0;
    }

    /** Returns [['k'=>..., 'cnt'=>int], ...]. */
    private function groupCounts(\PDO $db, string $sql): array
    {
        $out = [];
        foreach ($db->query($sql)->fetchAll() as $r) {
            $out[] = ['k' => (string) ($r['k'] ?? '—'), 'cnt' => (int) $r['cnt']];
        }
        return $out;
    }
}
