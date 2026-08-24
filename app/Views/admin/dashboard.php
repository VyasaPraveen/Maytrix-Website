<?php /** @var array $stats @var array $recentBookings @var array $recentEnrolments */
$statusTag = ['new' => 'tag-amber', 'contacted' => 'tag-grey', 'confirmed' => 'tag-green', 'closed' => 'tag-grey',
              'pending' => 'tag-amber', 'cancelled' => 'tag-red', 'waitlist' => 'tag-grey'];
?>
<div class="stat-grid">
  <div class="stat"><div class="label">New 1-to-1 Bookings</div><div class="value"><?= (int)$stats['newBookings'] ?></div><div class="sub">awaiting response</div></div>
  <div class="stat brass"><div class="label">Pending Enrolments</div><div class="value"><?= (int)$stats['pendingEnrol'] ?></div><div class="sub">need confirmation</div></div>
  <div class="stat"><div class="label">Confirmed Enrolments</div><div class="value"><?= (int)$stats['confirmedEnrol'] ?></div><div class="sub">active seats</div></div>
  <div class="stat"><div class="label">Open Batches</div><div class="value"><?= (int)$stats['openBatches'] ?></div><div class="sub">accepting students</div></div>
  <div class="stat"><div class="label">Students</div><div class="value"><?= (int)$stats['students'] ?></div><div class="sub">total records</div></div>
  <div class="stat rust"><div class="label">Unread Messages</div><div class="value"><?= (int)$stats['unreadMsgs'] ?></div><div class="sub">contact form</div></div>
</div>

<div class="panel">
  <div class="panel-head"><h3>Recent 1-to-1 Booking Requests</h3><a href="<?= e(admin_url('bookings')) ?>" class="btn btn-outline btn-sm">View all</a></div>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Name</th><th>Curriculum · Subject</th><th>Type</th><th>Status</th><th>Received</th><th></th></tr></thead>
      <tbody>
        <?php if (empty($recentBookings)): ?>
          <tr><td colspan="6" class="empty">No booking requests yet.</td></tr>
        <?php else: foreach ($recentBookings as $b): ?>
          <tr>
            <td><strong><?= e($b['name']) ?></strong><br><span class="muted"><?= e($b['email']) ?></span></td>
            <td><?= e($b['curriculum_name'] ?? '—') ?> · <?= e($b['subject_name'] ?? '—') ?></td>
            <td><?= e($b['class_type'] === 'one_to_one' ? '1-to-1' : 'Small-Group') ?></td>
            <td><span class="tag <?= $statusTag[$b['status']] ?? 'tag-grey' ?>"><?= e($b['status']) ?></span></td>
            <td class="muted"><?= e($b['created_at'] ? date('j M, H:i', strtotime($b['created_at'])) : '') ?></td>
            <td class="actions"><a href="<?= e(admin_url('bookings/' . $b['id'])) ?>" class="btn btn-outline btn-sm">Open</a></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<div class="panel">
  <div class="panel-head"><h3>Recent Enrolments</h3><a href="<?= e(admin_url('enrolments')) ?>" class="btn btn-outline btn-sm">View all</a></div>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Student</th><th>Batch</th><th>Status</th><th>Payment</th></tr></thead>
      <tbody>
        <?php if (empty($recentEnrolments)): ?>
          <tr><td colspan="4" class="empty">No enrolments yet.</td></tr>
        <?php else: foreach ($recentEnrolments as $en): ?>
          <tr>
            <td><strong><?= e($en['student_name']) ?></strong><br><span class="muted"><?= e($en['student_email']) ?></span></td>
            <td><?= e($en['batch_name']) ?></td>
            <td><span class="tag <?= $statusTag[$en['status']] ?? 'tag-grey' ?>"><?= e($en['status']) ?></span></td>
            <td><span class="tag <?= $en['payment_status'] === 'paid' ? 'tag-green' : 'tag-grey' ?>"><?= e($en['payment_status']) ?></span></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
