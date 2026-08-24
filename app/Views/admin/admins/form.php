<?php /** @var array|null $item @var array $errors @var array $old */
use App\Core\Csrf;
$isEdit = $item !== null;
$action = $isEdit ? admin_url('admins/' . $item['id']) : admin_url('admins');
$get = fn($k) => $old[$k] ?? ($item[$k] ?? '');
?>
<div class="panel" style="max-width:640px;">
  <div class="panel-head">
    <h3><?= $isEdit ? 'Edit' : 'New' ?> Admin User</h3>
    <a href="<?= e(admin_url('admins')) ?>" class="btn btn-outline btn-sm">← Back</a>
  </div>
  <div class="panel-body">
    <form method="post" action="<?= e($action) ?>">
      <?= Csrf::field() ?>
      <div class="form-field full">
        <label>Name *</label>
        <input type="text" name="name" value="<?= e($get('name')) ?>" class="<?= isset($errors['name']) ? 'invalid' : '' ?>">
        <?php if (isset($errors['name'])): ?><span class="err"><?= e($errors['name']) ?></span><?php endif; ?>
      </div>
      <div class="form-field full">
        <label>Email *</label>
        <input type="email" name="email" value="<?= e($get('email')) ?>" class="<?= isset($errors['email']) ? 'invalid' : '' ?>">
        <?php if (isset($errors['email'])): ?><span class="err"><?= e($errors['email']) ?></span><?php endif; ?>
      </div>
      <div class="form-field full">
        <label>Password <?= $isEdit ? '(leave blank to keep current)' : '*' ?></label>
        <input type="password" name="password" class="<?= isset($errors['password']) ? 'invalid' : '' ?>" autocomplete="new-password">
        <span class="hint">Minimum 8 characters.</span>
        <?php if (isset($errors['password'])): ?><span class="err"><?= e($errors['password']) ?></span><?php endif; ?>
      </div>
      <div class="form-field full">
        <label>Role *</label>
        <select name="role">
          <option value="admin" <?= $get('role') === 'admin' ? 'selected' : '' ?>>Admin (full access)</option>
          <option value="editor" <?= $get('role') === 'editor' ? 'selected' : '' ?>>Editor</option>
        </select>
      </div>
      <div class="check-row" style="margin-top:6px;">
        <input type="checkbox" name="is_active" value="1" id="active" <?= (!$isEdit || (int)($item['is_active'] ?? 1) === 1) ? 'checked' : '' ?>>
        <label for="active" style="font-weight:500;">Account active (can sign in)</label>
      </div>
      <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Save changes' : 'Create admin' ?></button>
        <a href="<?= e(admin_url('admins')) ?>" class="btn btn-outline">Cancel</a>
      </div>
    </form>
  </div>
</div>
