<?php
/** @var array $summaries @var string $driver */
$meta = [
    'web'   => ['title' => 'Website Content', 'ico' => '🌐', 'db' => 'maytrix_web',
                'desc' => 'Curricula, courses, batches, students, enrolments, bookings, blog, pages & settings.', 'class' => ''],
    'admin' => ['title' => 'Admin & Auth', 'ico' => '🔐', 'db' => 'maytrix_admin',
                'desc' => 'Admin accounts, audit log and login-throttle records. Contains password hashes.', 'class' => 'admin'],
];
?>
<p class="page-lead">Download a complete SQL backup (structure + data) of either database. Keep these files somewhere safe — an admin backup contains password hashes. Re-import a <code>.sql</code> file via phpMyAdmin (Hostinger) to restore.</p>

<div class="backup-grid">
  <?php foreach (['web', 'admin'] as $conn):
      $m = $meta[$conn]; $s = $summaries[$conn]; ?>
    <div class="backup-card <?= $m['class'] ?>">
      <h4><span class="db-ico"><?= $m['ico'] ?></span> <?= e($m['title']) ?></h4>
      <p><?= e($m['desc']) ?></p>
      <div class="backup-meta">
        <span>Database <b><?= e($m['db']) ?></b></span>
        <span>Tables <b><?= (int) $s['table_count'] ?></b></span>
        <span>Rows <b><?= number_format((int) $s['total_rows']) ?></b></span>
      </div>
      <a href="<?= e(admin_url('backups/' . $conn . '/download')) ?>" class="btn btn-primary btn-block">↓ Download <?= e($m['db']) ?>.sql</a>
    </div>
  <?php endforeach; ?>
</div>

<div class="note-box" style="margin-top:24px;">
  <strong>Current driver:</strong> <?= e(strtoupper($driver)) ?>.
  Dumps are generated on-demand from the live databases and are always current.
  For scheduled/automatic backups on Hostinger, also use <strong>hPanel → Files → Backups</strong>.
</div>

<div class="panel" style="margin-top:24px;">
  <div class="panel-head"><h3>What's inside each backup</h3></div>
  <div class="panel-body">
    <div class="panel-grid">
      <div>
        <h4 style="font-size:14px;">Website Content (<?= (int) $summaries['web']['table_count'] ?> tables)</h4>
        <table class="data" style="font-size:12.5px;">
          <tbody>
          <?php foreach ($summaries['web']['tables'] as $t => $n): ?>
            <tr><td><?= e($t) ?></td><td style="text-align:right;font-family:'IBM Plex Mono',monospace;"><?= number_format((int)$n) ?></td></tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div>
        <h4 style="font-size:14px;">Admin &amp; Auth (<?= (int) $summaries['admin']['table_count'] ?> tables)</h4>
        <table class="data" style="font-size:12.5px;">
          <tbody>
          <?php foreach ($summaries['admin']['tables'] as $t => $n): ?>
            <tr><td><?= e($t) ?></td><td style="text-align:right;font-family:'IBM Plex Mono',monospace;"><?= number_format((int)$n) ?></td></tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
