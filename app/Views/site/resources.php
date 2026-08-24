<?php /** @var array $posts */ ?>
<section>
  <div class="wrap">
    <span class="eyebrow">Resources / Blog</span>
    <h1 style="font-size:32px;">Notes from the tutoring room</h1>
    <p style="max-width:640px;margin-bottom:36px;">Short, syllabus-specific guidance from our tutors. New articles added regularly.</p>

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
