<?php /** @var array $rows */
$statusTag = ['new' => 'tag-amber', 'contacted' => 'tag-grey', 'confirmed' => 'tag-green', 'closed' => 'tag-grey'];
?>
<div class="panel">
  <div class="panel-head"><h3>1-to-1 Booking Requests <span class="muted">(<?= (int) ($pager->total ?? count($rows)) ?>)</span></h3></div>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Name</th><th>Curriculum · Subject</th><th>Type</th><th>Mode</th><th>Status</th><th>Received</th><th></th></tr></thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="7" class="empty">No booking requests yet.</td></tr>
        <?php else: foreach ($rows as $b): ?>
          <tr>
            <?php $listCountry = (!empty($b['student_country'])) ? $b['student_country'] : ($b['country'] ?? ''); ?>
            <td><strong><?= e($b['name']) ?></strong><br><span class="muted"><?= e($b['email']) ?><?= $listCountry ? ' · ' . e($listCountry) : '' ?></span></td>
            <td><?= e($b['curriculum_name'] ?? '—') ?> · <?= e($b['subject_name'] ?? '—') ?></td>
            <td><?= e($b['class_type'] === 'one_to_one' ? '1-to-1' : 'Small-Group') ?></td>
            <td class="muted"><?= e($b['mode_name'] ?? '—') ?></td>
            <td><span class="tag <?= $statusTag[$b['status']] ?? 'tag-grey' ?>"><?= e($b['status']) ?></span></td>
            <td class="muted"><?= e($b['created_at'] ? date('j M, H:i', strtotime($b['created_at'])) : '') ?></td>
            <td class="actions"><a href="<?= e(admin_url('bookings/' . $b['id'])) ?>" class="btn btn-teal btn-sm">Open</a></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  <?php include __DIR__ . '/../partials/pagination.php'; ?>
</div>
