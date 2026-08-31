<?php /** @var string $groupKey @var array $group @var array $values */
use App\Core\Csrf;
?>
<div class="page-actions" style="margin-bottom:16px;">
  <a href="<?= e(admin_url('content')) ?>" class="btn btn-outline btn-sm">← All page content</a>
</div>

<form method="post" action="<?= e(admin_url('content/' . $groupKey)) ?>">
  <?= Csrf::field() ?>
  <div class="panel">
    <div class="panel-head"><h3><?= e($group['label']) ?></h3></div>
    <div class="panel-body">
      <p class="muted" style="margin:0 0 18px;">Clear a field to reset it to the original wording.</p>
      <div class="form-grid">
        <?php foreach ($group['blocks'] as $key => $meta):
            $type = $meta['type'] ?? 'text';
            $override = $values[$key] ?? null;
            $val = ($override !== null && trim((string) $override) !== '') ? $override : (string) ($meta['default'] ?? '');
            $isDefault = ($override === null || trim((string) $override) === '');
        ?>
          <div class="form-field <?= $type === 'text' ? '' : 'full' ?>">
            <label>
              <?= e($meta['label']) ?>
              <?php if (!$isDefault): ?><span class="badge-edited">customised</span><?php endif; ?>
            </label>
            <?php if ($type === 'richtext'): ?>
              <textarea name="blocks[<?= e($key) ?>]" rows="6" data-richtext><?= e($val) ?></textarea>
            <?php elseif ($type === 'textarea'): ?>
              <textarea name="blocks[<?= e($key) ?>]" rows="3"><?= e($val) ?></textarea>
            <?php else: ?>
              <input type="text" name="blocks[<?= e($key) ?>]" value="<?= e($val) ?>">
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Save changes</button>
  <a href="<?= e(base_url()) ?>" target="_blank" class="btn btn-ghost btn-sm" style="margin-left:8px;">Preview site ↗</a>
</form>
