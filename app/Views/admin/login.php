<?php /** @var array $flash @var array $errors @var array $old */
use App\Core\Csrf;
?>
<div class="auth-wrap">
  <div class="auth-card">
    <div class="brand"><img src="<?= e(asset('img/logo.png')) ?>" alt="Maytrix Education" class="brand-img" width="600" height="217"></div>
    <div class="sub">Admin Dashboard — sign in to manage your website</div>

    <?php if (!empty($flash['error'])): ?><div class="alert alert-error"><?= e($flash['error']) ?></div><?php endif; ?>
    <?php if (!empty($flash['success'])): ?><div class="alert alert-success"><?= e($flash['success']) ?></div><?php endif; ?>

    <form method="post" action="<?= e(admin_url('login')) ?>">
      <?= Csrf::field() ?>
      <div class="form-field full" style="margin-bottom:16px;">
        <label>Email</label>
        <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" class="<?= isset($errors['email']) ? 'invalid' : '' ?>" autofocus required>
        <?php if (isset($errors['email'])): ?><span class="err"><?= e($errors['email']) ?></span><?php endif; ?>
      </div>
      <div class="form-field full" style="margin-bottom:20px;">
        <label>Password</label>
        <input type="password" name="password" class="<?= isset($errors['password']) ? 'invalid' : '' ?>" required>
        <?php if (isset($errors['password'])): ?><span class="err"><?= e($errors['password']) ?></span><?php endif; ?>
      </div>
      <button type="submit" class="btn btn-primary btn-block">Sign in</button>
    </form>
    <p class="muted" style="margin-top:20px;text-align:center;">
      <a href="<?= e(base_url()) ?>" style="color:var(--teal-dark);">← Back to website</a>
    </p>
  </div>
</div>
