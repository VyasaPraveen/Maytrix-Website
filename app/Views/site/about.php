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
      <a href="<?= e(base_url('curricula')) ?>" class="card card-link">
        <h3 style="font-size:17px;"><?= e(block('about.card1.title')) ?></h3>
        <p style="font-size:14px;"><?= e(block('about.card1.body')) ?></p>
        <span class="card-more">Explore curricula →</span>
      </a>
      <a href="<?= e(base_url('small-group')) ?>" class="card card-link">
        <h3 style="font-size:17px;"><?= e(block('about.card2.title')) ?></h3>
        <p style="font-size:14px;"><?= e(block('about.card2.body')) ?></p>
        <span class="card-more">See small-group classes →</span>
      </a>
      <a href="<?= e(base_url('resources')) ?>" class="card card-link">
        <h3 style="font-size:17px;"><?= e(block('about.card3.title')) ?></h3>
        <p style="font-size:14px;"><?= e(block('about.card3.body')) ?></p>
        <span class="card-more">Read resources →</span>
      </a>
    </div>
  </div>
</section>

<!-- ===================== MEET YOUR TUTOR ===================== -->
<section class="section alt">
  <div class="wrap">
    <div class="section-head center">
      <span class="pill-eyebrow"><?= e(block('about.tutor.eyebrow')) ?></span>
      <h2><?= e(block('about.tutor.title')) ?></h2>
    </div>
    <?php $tutorPhoto = trim(block('about.tutor.photo')); ?>
    <div class="tutor-card">
      <div class="tutor-photo">
        <?php if ($tutorPhoto !== ''): ?>
          <img src="<?= e(asset($tutorPhoto)) ?>" alt="<?= e(block('about.tutor.name')) ?>" loading="lazy">
        <?php else: ?>
          <svg viewBox="0 0 200 200" role="img" aria-label="Tutor portrait">
            <defs>
              <linearGradient id="tut-bg" x1="0" y1="0" x2="1" y2="1">
                <stop offset="0" stop-color="#EAF6FD"/><stop offset="1" stop-color="#D3E0F3"/>
              </linearGradient>
              <linearGradient id="tut-fg" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="#1E63A8"/><stop offset="1" stop-color="#0A2452"/>
              </linearGradient>
            </defs>
            <circle cx="100" cy="100" r="100" fill="url(#tut-bg)"/>
            <circle cx="100" cy="78" r="34" fill="url(#tut-fg)"/>
            <path d="M40 178 C40 130 74 118 100 118 C126 118 160 130 160 178 Z" fill="url(#tut-fg)"/>
          </svg>
        <?php endif; ?>
      </div>
      <div class="tutor-info">
        <h3><?= e(block('about.tutor.name')) ?></h3>
        <div class="tutor-role"><?= e(block('about.tutor.role')) ?></div>
        <p><?= e(block('about.tutor.bio')) ?></p>
        <a href="<?= e(base_url('book')) ?>" class="btn btn-primary btn-sm">Book a Free Consultation</a>
      </div>
    </div>
  </div>
</section>
