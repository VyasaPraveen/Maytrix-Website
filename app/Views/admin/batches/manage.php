<?php /** @var array $batch @var array $enrolments */
use App\Core\Csrf;
$statusTag = ['pending' => 'tag-amber', 'confirmed' => 'tag-green', 'cancelled' => 'tag-red', 'waitlist' => 'tag-grey'];
$max = (int) $batch['max_seats']; $avail = (int) $batch['available_seats'];
?>
<div style="margin-bottom:16px;"><a href="<?= e(admin_url('batches')) ?>" class="btn btn-outline btn-sm">← Back to batches</a></div>

<div class="stat-grid">
  <div class="stat"><div class="label">Confirmed seats</div><div class="value"><?= $max - $avail ?> / <?= $max ?></div><div class="sub">available: <?= $avail ?></div></div>
  <div class="stat brass"><div class="label">Mode</div><div class="value" style="font-size:20px;"><?= e($batch['mode_name'] ?? '—') ?></div></div>
  <div class="stat"><div class="label">Status</div><div class="value" style="font-size:20px;"><?= e($batch['status']) ?></div></div>
</div>

<div class="panel">
  <div class="panel-head"><h3><?= e($batch['name']) ?></h3><a href="<?= e(admin_url('batches/' . $batch['id'] . '/edit')) ?>" class="btn btn-outline btn-sm">Edit batch / Zoom link</a></div>
  <div class="panel-body">
    <p class="muted" style="margin:0 0 6px;"><strong>Schedule:</strong> <?= e($batch['schedule_text'] ?? 'TBC') ?> · <strong>Tutor:</strong> <?= e($batch['tutor_name'] ?? 'TBC') ?></p>
    <?php if (!empty($batch['zoom_link'])): ?>
      <p style="margin:0;"><strong>Zoom link:</strong> <a class="zoom-link" href="<?= e($batch['zoom_link']) ?>" target="_blank"><?= e($batch['zoom_link']) ?></a></p>
    <?php else: ?>
      <p class="tag tag-amber">No Zoom link set — add one via “Edit batch” so it can be shared with confirmed students.</p>
    <?php endif; ?>
  </div>
</div>

<div class="panel">
  <div class="panel-head"><h3>Enrolments (<?= count($enrolments) ?>)</h3></div>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Student</th><th>Status</th><th>Payment</th><th>Requested</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if (empty($enrolments)): ?>
          <tr><td colspan="5" class="empty">No enrolments yet for this batch.</td></tr>
        <?php else: foreach ($enrolments as $en): ?>
          <tr>
            <td><strong><?= e($en['student_name']) ?></strong><br><span class="muted"><?= e($en['student_email']) ?><?= $en['student_country'] ? ' · ' . e($en['student_country']) : '' ?></span></td>
            <td><span class="tag <?= $statusTag[$en['status']] ?? 'tag-grey' ?>"><?= e($en['status']) ?></span></td>
            <td><span class="tag <?= $en['payment_status'] === 'paid' ? 'tag-green' : 'tag-grey' ?>"><?= e($en['payment_status']) ?></span></td>
            <td class="muted"><?= e($en['created_at'] ? date('j M, H:i', strtotime($en['created_at'])) : '') ?></td>
            <td class="actions">
              <?php if ($en['status'] !== 'confirmed'): ?>
                <form method="post" action="<?= e(admin_url('enrolments/' . $en['id'] . '/confirm')) ?>" style="display:inline;" data-confirm="Confirm this enrolment? This consumes a seat and emails the class link to the student.">
                  <?= Csrf::field() ?><button class="btn btn-teal btn-sm">Confirm + share link</button>
                </form>
              <?php else: ?>
                <form method="post" action="<?= e(admin_url('enrolments/' . $en['id'] . '/cancel')) ?>" style="display:inline;" data-confirm="Cancel this enrolment and free the seat?">
                  <?= Csrf::field() ?><button class="btn btn-outline btn-sm">Cancel</button>
                </form>
              <?php endif; ?>
              <?php if ($en['payment_status'] !== 'paid'): ?>
                <form method="post" action="<?= e(admin_url('enrolments/' . $en['id'] . '/paid')) ?>" style="display:inline;">
                  <?= Csrf::field() ?><button class="btn btn-outline btn-sm">Mark paid</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
