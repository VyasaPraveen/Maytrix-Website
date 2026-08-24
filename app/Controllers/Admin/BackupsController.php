<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Flash;
use App\Core\Database;
use App\Models\AuditLog;
use App\Support\Backup;

/**
 * Database backups. Lets an admin download a full SQL dump (structure + data)
 * of either database — the content DB (maytrix_web) or the admin DB
 * (maytrix_admin) — for off-site safekeeping or migration.
 */
final class BackupsController extends AdminController
{
    private const CONNS = ['web', 'admin'];

    public function index(Request $request): void
    {
        $summaries = [];
        foreach (self::CONNS as $conn) {
            $summaries[$conn] = Backup::summary($conn);
        }
        $this->renderAdmin('admin/backups/index', [
            'pageTitle'  => 'Backups',
            'activeMenu' => 'backups',
            'summaries'  => $summaries,
            'driver'     => Database::driver(),
        ]);
    }

    /** Stream a SQL dump of one database as a file download. */
    public function download(Request $request, string $conn): void
    {
        if (!in_array($conn, self::CONNS, true)) {
            Flash::error('Unknown database.');
            $this->redirect(admin_url('backups'));
            return;
        }

        try {
            $sql = Backup::dump($conn);
        } catch (\Throwable $e) {
            log_message('Backup failed [' . $conn . ']: ' . $e->getMessage());
            Flash::error('Could not generate the backup. Please try again.');
            $this->redirect(admin_url('backups'));
            return;
        }

        AuditLog::record('backup', $conn, null);

        $filename = 'maytrix_' . $conn . '_' . date('Ymd_His') . '.sql';
        // Discard any buffered output so the download is clean.
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        header('Content-Type: application/sql; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($sql));
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
        echo $sql;
        exit;
    }
}
