<?php /** @var array $groups @var array $values */
use App\Core\Csrf;
?>
<form method="post" action="<?= e(admin_url('settings')) ?>">
  <?= Csrf::field() ?>
  <?php foreach ($groups as $groupName => $fields): ?>
    <div class="panel">
      <div class="panel-head"><h3><?= e($groupName) ?></h3></div>
      <div class="panel-body">
        <div class="form-grid">
          <?php foreach ($fields as $key => $meta):
              $val = $values[$key] ?? '';
              $type = $meta['type'] ?? 'text';
          ?>
            <div class="form-field <?= $type === 'textarea' ? 'full' : '' ?>">
              <label><?= e($meta['label']) ?></label>
              <?php if (!empty($meta['hint'])): ?><span class="hint"><?= e($meta['hint']) ?></span><?php endif; ?>
              <?php if ($type === 'textarea'): ?>
                <textarea name="<?= e($key) ?>" rows="3"><?= e($val) ?></textarea>
              <?php else: ?>
                <input type="text" name="<?= e($key) ?>" value="<?= e($val) ?>">
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  <button type="submit" class="btn btn-primary">Save all settings</button>
</form>
