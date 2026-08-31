<?php /** @var array|null $page */ ?>
<section class="page-hero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= e(base_url()) ?>">Home</a><span>›</span> About</div>
    <span class="pill-eyebrow">About Maytrix</span>
    <h1><?= e($page['title'] ?? 'About Maytrix Education') ?></h1>
  </div>
</section>
<section>
  <div class="wrap" style="max-width:820px;">
    <div class="content-body" style="font-size:17px;">
      <?= $page['body_html'] ?? '' ?>
    </div>

    <div class="grid-3" style="margin-top:40px;">
      <div class="card">
        <h3 style="font-size:17px;"><?= e(block('about.card1.title')) ?></h3>
        <p style="font-size:14px;"><?= e(block('about.card1.body')) ?></p>
      </div>
      <div class="card">
        <h3 style="font-size:17px;"><?= e(block('about.card2.title')) ?></h3>
        <p style="font-size:14px;"><?= e(block('about.card2.body')) ?></p>
      </div>
      <div class="card">
        <h3 style="font-size:17px;"><?= e(block('about.card3.title')) ?></h3>
        <p style="font-size:14px;"><?= e(block('about.card3.body')) ?></p>
      </div>
    </div>
  </div>
</section>
