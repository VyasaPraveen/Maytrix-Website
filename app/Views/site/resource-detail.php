<?php /** @var array $post */ ?>
<section>
  <div class="wrap" style="max-width:720px;">
    <a href="<?= e(base_url('resources')) ?>" class="btn-ghost" style="font-size:12.5px;display:inline-block;">← Back to Resources</a>
    <div class="kicker" style="font-family:'IBM Plex Mono',monospace;font-size:11px;letter-spacing:.06em;text-transform:uppercase;color:var(--brass-dark);margin:18px 0 10px;"><?= e($post['kicker'] ?? '') ?></div>
    <h1 style="font-size:30px;"><?= e($post['title']) ?></h1>
    <?php if (!empty($post['published_at'])): ?>
      <p class="muted"><?= e(date('j M Y', strtotime($post['published_at']))) ?></p>
    <?php endif; ?>
    <div class="content-body" style="font-size:16px;margin-top:20px;">
      <?= $post['body_html'] ?? ('<p>' . e($post['excerpt'] ?? '') . '</p>') ?>
    </div>
    <div style="margin-top:40px;">
      <a href="<?= e(base_url('book')) ?>" class="btn btn-primary">Book a Free Consultation</a>
    </div>
  </div>
</section>
