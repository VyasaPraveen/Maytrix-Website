<?php /** @var array $batches */
$statusTag = ['draft' => 'tag-grey', 'open' => 'tag-green', 'full' => 'tag-amber', 'closed' => 'tag-grey', 'completed' => 'tag-grey'];
?>
<div class="panel">
  <div class="panel-head">
    <h3>Batches <span class="muted">(<?= (int) ($pager->total ?? count($batches)) ?>)</span></h3>
    <a href="<?= e(admin_url('batches/create')) ?>" class="btn btn-primary btn-sm">+ New Batch</a>
  </div>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Batch</th><th>Course</th><th>Mode</th><th>Schedule</th><th>Seats</th><th>Status</th><th>Zoom</th><th></th></tr></thead>
      <tbody>
        <?php if (empty($batches)): ?>
          <tr><td colspan="8" class="empty">No batches yet. <a href="<?= e(admin_url('batches/create')) ?>">Create the first batch →</a></td></tr>
        <?php else: foreach ($batches as $b):
            $max = (int) $b['max_seats']; $avail = (int) $b['available_seats']; $taken = $max - $avail;
            $fill = $max > 0 ? round($taken / $max * 100) : 0;
            $barClass = $avail <= 1 ? 'high' : ($avail <= floor($max / 2) ? 'mid' : '');
        ?>
          <tr>
            <td><strong><?= e($b['name']) ?></strong><br><span class="muted"><?= e($b['start_date'] ? date('j M Y', strtotime($b['start_date'])) : 'No date') ?></span></td>
            <td><?= e($b['course_title'] ?? '—') ?></td>
            <td><span class="tag tag-grey"><?= e($b['mode_name'] ?? '—') ?></span></td>
            <td class="muted"><?= e($b['schedule_text'] ?? '—') ?></td>
            <td>
              <span class="seat-mini"><?= $avail ?>/<?= $max ?> left</span>
              <div class="seat-bar"><i class="<?= $barClass ?>" style="width:<?= $fill ?>%;"></i></div>
            </td>
            <td><span class="tag <?= $statusTag[$b['status']] ?? 'tag-grey' ?>"><?= e($b['status']) ?></span></td>
            <td><?= !empty($b['zoom_link']) ? '<span class="tag tag-green">Set</span>' : '<span class="tag tag-amber">Missing</span>' ?></td>
            <td class="actions">
              <a href="<?= e(admin_url('batches/' . $b['id'] . '/manage')) ?>" class="btn btn-teal btn-sm">Manage</a>
              <a href="<?= e(admin_url('batches/' . $b['id'] . '/edit')) ?>" class="btn btn-outline btn-sm">Edit</a>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  <?php include __DIR__ . '/../partials/pagination.php'; ?>
</div>
