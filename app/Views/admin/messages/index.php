<?php /** @var array $rows */ ?>
<div class="panel">
  <div class="panel-head"><h3>Contact Messages <span class="muted">(<?= (int) ($pager->total ?? count($rows)) ?>)</span></h3></div>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>From</th><th>Curriculum</th><th>Preview</th><th>Received</th><th></th></tr></thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="5" class="empty">No messages yet.</td></tr>
        <?php else: foreach ($rows as $m): ?>
          <tr style="<?= (int)$m['is_read'] === 0 ? 'font-weight:600;' : '' ?>">
            <td>
              <?= (int)$m['is_read'] === 0 ? '<span class="tag tag-amber">New</span> ' : '' ?>
              <?= e($m['name']) ?><br><span class="muted" style="font-weight:400;"><?= e($m['email']) ?></span>
            </td>
            <td class="muted"><?= e($m['curriculum'] ?: '—') ?></td>
            <td class="muted" style="font-weight:400;"><?= e(mb_strimwidth($m['message'] ?? '', 0, 60, '…')) ?></td>
            <td class="muted" style="font-weight:400;"><?= e($m['created_at'] ? date('j M, H:i', strtotime($m['created_at'])) : '') ?></td>
            <td class="actions"><a href="<?= e(admin_url('messages/' . $m['id'])) ?>" class="btn btn-outline btn-sm">Read</a></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  <?php include __DIR__ . '/../partials/pagination.php'; ?>
</div>
