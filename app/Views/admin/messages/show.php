<?php /** @var array $msg */
use App\Core\Csrf;
?>
<div style="margin-bottom:16px;"><a href="<?= e(admin_url('messages')) ?>" class="btn btn-outline btn-sm">← Back to messages</a></div>
<div class="panel" style="max-width:720px;">
  <div class="panel-head"><h3>Message from <?= e($msg['name']) ?></h3></div>
  <div class="panel-body">
    <table class="data">
      <tr><th>Name</th><td><?= e($msg['name']) ?></td></tr>
      <tr><th>Email</th><td><a href="mailto:<?= e($msg['email']) ?>"><?= e($msg['email']) ?></a></td></tr>
      <tr><th>Country</th><td><?= e($msg['country'] ?: '—') ?></td></tr>
      <tr><th>Curriculum</th><td><?= e($msg['curriculum'] ?: '—') ?></td></tr>
      <tr><th>Received</th><td><?= e($msg['created_at']) ?></td></tr>
    </table>
    <div style="margin-top:18px;padding:16px;background:var(--paper-deep);border-radius:6px;">
      <?= nl2br(e($msg['message'] ?? '')) ?>
    </div>
    <div class="form-actions">
      <a href="mailto:<?= e($msg['email']) ?>" class="btn btn-primary">Reply by email</a>
      <form method="post" action="<?= e(admin_url('messages/' . $msg['id'] . '/delete')) ?>" data-confirm="Delete this message?" style="display:inline;">
        <?= Csrf::field() ?><button class="btn btn-danger">Delete</button>
      </form>
    </div>
  </div>
</div>
