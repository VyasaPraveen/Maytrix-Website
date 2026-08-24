<?php /** @var array $cfg @var array $rows @var array $lookups */
$columns = $cfg['columns'];
?>
<div class="panel">
  <div class="panel-head">
    <h3><?= e($cfg['title']) ?> <span class="muted">(<?= (int) ($pager->total ?? count($rows)) ?>)</span></h3>
    <a href="<?= e(admin_url($cfg['route'] . '/create')) ?>" class="btn btn-primary btn-sm">+ New <?= e($cfg['singular']) ?></a>
  </div>
  <div class="table-wrap">
    <table class="data">
      <thead>
        <tr>
          <?php foreach ($columns as $label): ?><th><?= e($label) ?></th><?php endforeach; ?>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="<?= count($columns) + 1 ?>" class="empty">No <?= e(strtolower($cfg['title'])) ?> yet. <a href="<?= e(admin_url($cfg['route'] . '/create')) ?>">Create the first one →</a></td></tr>
        <?php else: foreach ($rows as $row): ?>
          <tr>
            <?php foreach ($columns as $field => $label):
                $val = $row[$field] ?? '';
                // FK lookup display.
                if (isset($lookups[$field]) && $val !== '' && $val !== null) {
                    $val = $lookups[$field][$val] ?? $val;
                }
            ?>
              <td>
                <?php if (is_string($val) && (str_starts_with($field, 'is_') || $field === 'is_published' || $field === 'is_active')): ?>
                  <?= $val ? '<span class="tag tag-green">Yes</span>' : '<span class="tag tag-grey">No</span>' ?>
                <?php elseif (in_array($field, ['is_published', 'is_active'], true) || is_bool($val)): ?>
                  <?= $val ? '<span class="tag tag-green">Yes</span>' : '<span class="tag tag-grey">No</span>' ?>
                <?php else: ?>
                  <?= e(mb_strimwidth(is_array($val) ? json_encode($val) : (string) $val, 0, 70, '…')) ?>
                <?php endif; ?>
              </td>
            <?php endforeach; ?>
            <td class="actions">
              <a href="<?= e(admin_url($cfg['route'] . '/' . $row['id'] . '/edit')) ?>" class="btn btn-outline btn-sm">Edit</a>
              <form method="post" action="<?= e(admin_url($cfg['route'] . '/' . $row['id'] . '/delete')) ?>" style="display:inline;" data-confirm="Delete this <?= e(strtolower($cfg['singular'])) ?>? This cannot be undone.">
                <?= \App\Core\Csrf::field() ?>
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  <?php include __DIR__ . '/../partials/pagination.php'; ?>
</div>
