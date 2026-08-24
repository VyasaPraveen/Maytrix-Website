<?php /** @var array $cfg @var array|null $item @var array $options @var array $errors @var array $old */
use App\Core\Csrf;
$isEdit = $item !== null;
$action = $isEdit ? admin_url($cfg['route'] . '/' . $item['id']) : admin_url($cfg['route']);
// Value resolver: old input (after validation) > existing item > default.
$val = function (array $f) use ($item, $old) {
    $name = $f['name'];
    if (array_key_exists($name, $old)) return $old[$name];
    if ($item !== null && array_key_exists($name, $item)) {
        $v = $item[$name];
        if (is_array($v)) return json_encode($v, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        return $v;
    }
    return $f['default'] ?? '';
};
?>
<div class="panel">
  <div class="panel-head">
    <h3><?= $isEdit ? 'Edit' : 'New' ?> <?= e($cfg['singular']) ?></h3>
    <a href="<?= e(admin_url($cfg['route'])) ?>" class="btn btn-outline btn-sm">← Back to list</a>
  </div>
  <div class="panel-body">
    <form method="post" action="<?= e($action) ?>">
      <?= Csrf::field() ?>
      <div class="form-grid">
        <?php foreach ($cfg['fields'] as $f):
            $type = $f['type'] ?? 'text';
            $name = $f['name'];
            $value = $val($f);
            $full = !empty($f['full']) || in_array($type, ['textarea', 'richtext', 'json'], true);
            $invalid = isset($errors[$name]) ? 'invalid' : '';
        ?>
          <div class="form-field <?= $full ? 'full' : '' ?>">
            <label><?= e($f['label']) ?><?= !empty($f['rules']) && str_contains($f['rules'], 'required') ? ' *' : '' ?></label>
            <?php if (!empty($f['hint'])): ?><span class="hint"><?= e($f['hint']) ?></span><?php endif; ?>

            <?php if ($type === 'textarea' || $type === 'richtext'): ?>
              <textarea name="<?= e($name) ?>" rows="<?= $type === 'richtext' ? 10 : 4 ?>" class="<?= $invalid ?>" placeholder="<?= e($f['placeholder'] ?? '') ?>"><?= e($value) ?></textarea>

            <?php elseif ($type === 'json'): ?>
              <textarea name="<?= e($name) ?>" rows="6" class="mono <?= $invalid ?>" placeholder='<?= e($f['placeholder'] ?? '["item one","item two"]') ?>'><?= e($value) ?></textarea>

            <?php elseif ($type === 'country'):
                $countries = require BASE_PATH . '/app/Support/countries.php';
                $tzTarget = $f['tz_target'] ?? 'timezone';
            ?>
              <select name="<?= e($name) ?>" class="<?= $invalid ?>"
                      data-country-select data-tz-target="<?= e($tzTarget) ?>"
                      data-tz-map='<?= e(json_encode($countries, JSON_UNESCAPED_UNICODE)) ?>'>
                <option value="">— Select country —</option>
                <?php foreach ($countries as $countryName => $tz): ?>
                  <option value="<?= e($countryName) ?>" <?= (string) $value === (string) $countryName ? 'selected' : '' ?>><?= e($countryName) ?></option>
                <?php endforeach; ?>
                <?php if ($value !== '' && !isset($countries[$value])): ?>
                  <option value="<?= e($value) ?>" selected><?= e($value) ?></option>
                <?php endif; ?>
              </select>

            <?php elseif ($type === 'select' || $type === 'select-int'): ?>
              <select name="<?= e($name) ?>" class="<?= $invalid ?>">
                <?php if (!empty($f['nullable'])): ?><option value="">— none —</option><?php endif; ?>
                <?php
                  $opts = $f['options'] ?? ($options[$f['options_key']] ?? []);
                  foreach ($opts as $ov => $ol): ?>
                  <option value="<?= e($ov) ?>" <?= (string) $value === (string) $ov ? 'selected' : '' ?>><?= e($ol) ?></option>
                <?php endforeach; ?>
              </select>

            <?php elseif ($type === 'checkbox'): ?>
              <div class="check-row">
                <input type="checkbox" name="<?= e($name) ?>" value="1" <?= $value ? 'checked' : '' ?>>
                <span class="muted"><?= e($f['checkbox_label'] ?? 'Enabled') ?></span>
              </div>

            <?php elseif ($type === 'date'): ?>
              <input type="date" name="<?= e($name) ?>" value="<?= e($value ? date('Y-m-d', strtotime((string)$value)) : '') ?>" class="<?= $invalid ?>">

            <?php elseif ($type === 'number'): ?>
              <input type="number" step="<?= e($f['step'] ?? '1') ?>" name="<?= e($name) ?>" value="<?= e($value) ?>" class="<?= $invalid ?>">

            <?php else: /* text, url, slug, email */ ?>
              <input type="<?= $type === 'url' ? 'url' : ($type === 'email' ? 'email' : 'text') ?>"
                     name="<?= e($name) ?>" value="<?= e($value) ?>" class="<?= $invalid ?>"
                     placeholder="<?= e($f['placeholder'] ?? '') ?>"
                     <?= !empty($f['slug_source']) ? 'id="field-' . e($name) . '"' : '' ?>
                     <?= !empty($f['makes_slug']) ? 'data-slug-target="#field-' . e($f['makes_slug']) . '"' : '' ?>>
            <?php endif; ?>

            <?php if (isset($errors[$name])): ?><span class="err"><?= e($errors[$name]) ?></span><?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Save changes' : 'Create ' . e($cfg['singular']) ?></button>
        <a href="<?= e(admin_url($cfg['route'])) ?>" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
