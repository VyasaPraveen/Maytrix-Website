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
        <h3 style="font-size:17px;">Syllabus-first</h3>
        <p style="font-size:14px;">Every tutor teaches to the specific board and paper structure a student is sitting — not a generic curriculum.</p>
      </div>
      <div class="card">
        <h3 style="font-size:17px;">Small by design</h3>
        <p style="font-size:14px;">Group classes are capped at 6 students so every question still gets answered live.</p>
      </div>
      <div class="card">
        <h3 style="font-size:17px;">Built to grow with you</h3>
        <p style="font-size:14px;">Starting as a focused tutoring brand, with a student portal and progress tracking planned as the next step.</p>
      </div>
    </div>
  </div>
</section>
