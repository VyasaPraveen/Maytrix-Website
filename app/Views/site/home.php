<?php /** @var array $settings @var array $curricula */ ?>
<section class="hero">
  <div class="wrap hero-grid">
    <div>
      <span class="eyebrow"><?= e($settings['hero_eyebrow'] ?? '') ?></span>
      <h1><?= e($settings['hero_title'] ?? '') ?></h1>
      <p class="lede"><?= e($settings['hero_lede'] ?? '') ?></p>
      <div class="hero-ctas">
        <a href="<?= e(base_url('book')) ?>" class="btn btn-primary">Book a Free Consultation</a>
        <a href="<?= e(base_url('programmes')) ?>" class="btn btn-outline">See the Course Matrix</a>
      </div>
      <div class="hero-trust">TIME ZONES COVERED: IST · GST · GMT · CET &nbsp;|&nbsp; CLASSES RUN LIVE ON ZOOM</div>
    </div>

    <div class="worked-panel">
      <div class="worked-head">
        <span class="label">Live worked example</span>
        <div class="subject-tabs">
          <button type="button" class="active" data-worked="math">Math</button>
          <button type="button" data-worked="physics">Physics</button>
        </div>
      </div>
      <div class="worked-body" id="workedBody"><!-- injected by JS --></div>
    </div>
  </div>
</section>

<section class="tight">
  <div class="wrap matrix-wrap">
    <div class="matrix-eq mono">M&nbsp;=</div>
    <div class="bracket" style="flex:1;">
      <div class="matrix-grid">
        <div class="cell"><div class="num"><?= e($settings['stat_students'] ?? '500+') ?></div><div class="cap">Students taught</div></div>
        <div class="cell"><div class="num"><?= e($settings['stat_countries'] ?? '12') ?></div><div class="cap">Countries reached</div></div>
        <div class="cell"><div class="num"><?= e($settings['stat_grades'] ?? '+1.8') ?></div><div class="cap">Avg. grade bands gained</div></div>
        <div class="cell"><div class="num"><?= e($settings['stat_curricula'] ?? '4') ?></div><div class="cap">Curricula covered</div></div>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">Curricula</span>
      <h2>Built around your exact syllabus</h2>
      <p>Not general tutoring — subject specialists who teach to the mark scheme you're actually sitting.</p>
    </div>
    <div class="grid-4">
      <?php foreach ($curricula as $c): ?>
        <div class="card">
          <span class="tag"><?= e($c['short_name'] ?: $c['name']) ?></span>
          <h3><?= e($c['name']) ?></h3>
          <p style="font-size:14px;"><?= e($c['tagline'] ?? '') ?></p>
          <div class="subjects"><span>Mathematics</span><span>Physics</span></div>
          <a href="<?= e(base_url('curricula')) ?>" class="btn-ghost">Explore <?= e($c['short_name'] ?: $c['name']) ?> →</a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section>
  <div class="wrap">
    <div class="section-head">
      <span class="eyebrow">How it works</span>
      <h2>From enquiry to your first class</h2>
    </div>
    <ol class="steps">
      <li><h4>Choose curriculum &amp; subject</h4><p>Tell us your board, level and subject — IB, IGCSE or A Level, Maths or Physics.</p></li>
      <li><h4>Pick 1-to-1 or Small-Group</h4><p>Personal pace, or a small cohort of up to 6 students at a similar level.</p></li>
      <li><h4>Book your slot</h4><p>Submit your requirement or book a free consultation call directly.</p></li>
      <li><h4>Join on Zoom</h4><p>Get your confirmation, class link and start working through real problems.</p></li>
    </ol>
  </div>
</section>

<section class="tight">
  <div class="wrap quote">
    <div class="bracket brass">
      <blockquote>"My daughter went from dreading Physics past papers to actually asking for extra ones. Her tutor teaches the Cambridge mark scheme, not just the topic."</blockquote>
    </div>
    <div class="who">— Parent of an AS &amp; A Level student, Dubai</div>
  </div>
</section>

<section class="tight" style="border-bottom:none;">
  <div class="wrap" style="text-align:center;">
    <h2 style="font-size:28px;">Ready to see where your child stands?</h2>
    <p style="max-width:480px;margin:0 auto 22px;">Book a free 20-minute consultation. We'll assess the syllabus, the gap, and recommend 1-to-1 or small-group classes.</p>
    <a href="<?= e(base_url('book')) ?>" class="btn btn-primary">Book a Free Consultation</a>
  </div>
</section>
