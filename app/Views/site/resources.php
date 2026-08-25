<?php /** @var array $posts */ ?>
<section class="page-hero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= e(base_url()) ?>">Home</a><span>›</span> Resources</div>
    <span class="pill-eyebrow">Resources / Blog</span>
    <h1>Notes from the tutoring room</h1>
    <p>Short, syllabus-specific guidance from our tutors. New articles added regularly.</p>
  </div>
</section>
<section>
  <div class="wrap">
    <?php if (empty($posts)): ?>
      <div class="card"><p style="margin:0;">Articles are on the way — check back soon.</p></div>
    <?php else: ?>
      <div class="grid-3">
        <?php foreach ($posts as $p): ?>
          <a class="article" href="<?= e(base_url('resources/' . $p['slug'])) ?>" style="display:block;">
            <div class="kicker"><?= e($p['kicker'] ?? '') ?></div>
            <h3><?= e($p['title']) ?></h3>
            <p style="font-size:13.5px;"><?= e($p['excerpt'] ?? '') ?></p>
            <div class="soon">Read article →</div>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>
