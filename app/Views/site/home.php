<?php
/** @var array $settings @var array $curricula @var array $classes @var array $posts */
$brand = $settings['brand_name'] ?? 'Maytrix Education';

// ---- inline icon set (stroke-based, currentColor) ----
$ic = [
  'target'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="5"/><circle cx="12" cy="12" r="1"/></svg>',
  'badge'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 15a6 6 0 100-12 6 6 0 000 12z"/><path d="M8.5 13.5L7 22l5-3 5 3-1.5-8.5"/></svg>',
  'users'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>',
  'video'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="6" width="14" height="12" rx="2"/><path d="M22 8l-6 4 6 4V8z"/></svg>',
  'chart'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="M7 14l4-4 3 3 5-6"/></svg>',
  'clock'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>',
  'globe'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a15 15 0 010 18 15 15 0 010-18z"/></svg>',
  'book'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>',
  'shield'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>',
  'check'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6L9 17l-5-5"/></svg>',
  'calendar' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>',
  'seat'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 9V6a2 2 0 00-2-2H6a2 2 0 00-2 2v3"/><path d="M2 11a2 2 0 012 2v3h16v-3a2 2 0 114 0"/><path d="M4 18v2M20 18v2"/></svg>',
  'penrose'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/></svg>',
];
$sym = ['INR'=>'₹','USD'=>'$','GBP'=>'£','EUR'=>'€','AED'=>'AED '];
?>

<!-- ===================== HERO ===================== -->
<section class="mx-hero">
  <div class="wrap mx-hero-grid">
    <div class="mx-hero-copy">
      <span class="pill-eyebrow"><?= e($settings['hero_eyebrow'] ?? 'IB · IGCSE · A Level — Maths & Physics') ?></span>
      <h1>Master <em>Maths &amp; Physics</em> with specialist online tutors.</h1>
      <p class="lede"><?= e($settings['hero_lede'] ?? 'Personal 1-to-1 and small-group classes for IB, IBMYP, Cambridge IGCSE and AS & A Level — taught to your exact exam board and mark scheme, live on Zoom.') ?></p>
      <div class="mx-hero-ctas">
        <a href="<?= e(base_url('book')) ?>" class="btn btn-primary">Book a Free Consultation</a>
        <a href="<?= e(base_url('programmes')) ?>" class="btn btn-outline">Explore Courses</a>
      </div>
      <div class="rating-inline">
        <div class="avatars"><span>SR</span><span>NW</span><span>ZK</span><span>+</span></div>
        <div>
          <div class="stars">★★★★★</div>
          <div class="who"><b><?= e($settings['stat_students'] ?? '500+') ?> students</b> across <?= e($settings['stat_countries'] ?? '12') ?> countries</div>
        </div>
      </div>
    </div>
    <div class="mx-hero-art">
      <img src="<?= e(asset('img/hero-illustration.svg')) ?>" alt="Live online Maths and Physics lesson" width="580" height="540">
    </div>
  </div>
</section>

<!-- ===================== TRUST STRIP ===================== -->
<div class="trust-strip">
  <div class="wrap">
    <span class="lbl">Exam boards we teach</span>
    <div class="boards">
      <span>IB Diploma</span><span>IBMYP</span><span>Cambridge IGCSE</span><span>Cambridge AS &amp; A Level</span>
    </div>
  </div>
</div>

<!-- ===================== FEATURES ===================== -->
<section>
  <div class="wrap">
    <div class="section-head center">
      <span class="pill-eyebrow"><?= e(block('home.features.eyebrow')) ?></span>
      <h2><?= e(block('home.features.title')) ?></h2>
      <p><?= e(block('home.features.intro')) ?></p>
    </div>
    <div class="feature-cards">
      <div class="feature-card">
        <div class="feature-ico"><?= $ic['target'] ?></div>
        <h3><?= e(block('home.feature1.title')) ?></h3>
        <p><?= e(block('home.feature1.body')) ?></p>
      </div>
      <div class="feature-card">
        <div class="feature-ico azure"><?= $ic['users'] ?></div>
        <h3><?= e(block('home.feature2.title')) ?></h3>
        <p><?= e(block('home.feature2.body')) ?></p>
      </div>
      <div class="feature-card">
        <div class="feature-ico navy"><?= $ic['video'] ?></div>
        <h3><?= e(block('home.feature3.title')) ?></h3>
        <p><?= e(block('home.feature3.body')) ?></p>
      </div>
    </div>
  </div>
</section>

<!-- ===================== MARQUEE ===================== -->
<div class="marquee" aria-hidden="true">
  <div class="marquee-track">
    <div class="marquee-item">Mathematics <span>✦</span> Physics <span>✦</span> IB Diploma <span>✦</span> IBMYP <span>✦</span> Cambridge IGCSE <span>✦</span> AS &amp; A Level <span>✦</span> Past-paper mastery <span>✦</span></div>
    <div class="marquee-item">Mathematics <span>✦</span> Physics <span>✦</span> IB Diploma <span>✦</span> IBMYP <span>✦</span> Cambridge IGCSE <span>✦</span> AS &amp; A Level <span>✦</span> Past-paper mastery <span>✦</span></div>
  </div>
</div>

<!-- ===================== RESULTS SPLIT ===================== -->
<section>
  <div class="wrap split">
    <div class="split-art">
      <img src="<?= e(asset('img/results-illustration.svg')) ?>" alt="Grade improvement over a term" width="520" height="480" loading="lazy">
    </div>
    <div class="split-copy">
      <span class="pill-eyebrow"><?= e(block('home.results.eyebrow')) ?></span>
      <h2><?= e(block('home.results.title')) ?></h2>
      <p><?= e(block('home.results.intro')) ?></p>
      <ul class="checklist">
        <li><span class="check-ico"><?= $ic['check'] ?></span><span><b>Diagnostic first.</b> We assess the syllabus, the gap and the target grade before the first lesson.</span></li>
        <li><span class="check-ico"><?= $ic['check'] ?></span><span><b>Mark-scheme trained tutors</b> who teach the technique examiners reward, not just the topic.</span></li>
        <li><span class="check-ico"><?= $ic['check'] ?></span><span><b>Past-paper practice</b> every week, with feedback on exactly where marks are won and lost.</span></li>
      </ul>
      <div class="mini-counters">
        <div class="mc"><div class="n"><em><?= e($settings['stat_grades'] ?? '+1.8') ?></em></div><div class="l">avg. grade bands gained</div></div>
        <div class="mc"><div class="n"><?= e($settings['stat_students'] ?? '500+') ?></div><div class="l">students taught</div></div>
        <div class="mc"><div class="n"><?= e($settings['stat_countries'] ?? '12') ?></div><div class="l">countries reached</div></div>
        <div class="mc"><div class="n"><?= e($settings['stat_group'] ?? '6') ?></div><div class="l">max small-group size</div></div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== CURRICULA ===================== -->
<section class="section alt">
  <div class="wrap">
    <div class="section-head center">
      <span class="pill-eyebrow"><?= e(block('home.curricula.eyebrow')) ?></span>
      <h2><?= e(block('home.curricula.title')) ?></h2>
      <p><?= e(block('home.curricula.intro')) ?></p>
    </div>
    <div class="curr-grid">
      <?php foreach ($curricula as $c): ?>
        <div class="curr-card">
          <div class="badge-ico"><?= e(strtoupper(substr($c['short_name'] ?: $c['code'] ?: $c['name'], 0, 3))) ?></div>
          <h3><?= e($c['name']) ?></h3>
          <p><?= e($c['tagline'] ?: 'Mathematics & Physics, taught to the ' . $c['name'] . ' specification.') ?></p>
          <a href="<?= e(base_url('curricula')) ?>" class="go">Explore <?= e($c['short_name'] ?: $c['name']) ?> <?= $ic['penrose'] === '' ? '' : '→' ?></a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ===================== WHY CHOOSE ===================== -->
<section>
  <div class="wrap">
    <div class="section-head center">
      <span class="pill-eyebrow"><?= e(block('home.why.eyebrow')) ?></span>
      <h2><?= e(block('home.why.title')) ?></h2>
    </div>
    <div class="why-grid">
      <div class="why-item"><div class="wi"><?= $ic['badge'] ?></div><div><h4>Subject-specialist tutors</h4><p>Maths &amp; Physics only — taught by people who know these two subjects and their boards inside out.</p></div></div>
      <div class="why-item"><div class="wi"><?= $ic['chart'] ?></div><div><h4>Progress you can see</h4><p>Clear starting point, target grade and regular past-paper checkpoints so improvement is visible.</p></div></div>
      <div class="why-item"><div class="wi"><?= $ic['globe'] ?></div><div><h4>Built for time zones</h4><p>Scheduling that works across IST, GST, GMT and CET — ideal for internationally-schooled students.</p></div></div>
      <div class="why-item"><div class="wi"><?= $ic['book'] ?></div><div><h4>Resources included</h4><p>Worked solutions, topic notes and curated past papers shared after every class.</p></div></div>
      <div class="why-item"><div class="wi"><?= $ic['clock'] ?></div><div><h4>Flexible formats</h4><p>Weekly classes, exam-season intensives or one-off crash sessions before a paper.</p></div></div>
      <div class="why-item"><div class="wi"><?= $ic['shield'] ?></div><div><h4>Safe &amp; reliable</h4><p>Verified tutors, secure booking and confirmations, and a free consultation before you commit.</p></div></div>
    </div>
  </div>
</section>

<!-- ===================== COUNTER BAND ===================== -->
<section class="counter-band">
  <div class="wrap">
    <div class="counter-grid">
      <div class="cg"><div class="n"><?= e($settings['stat_students'] ?? '500+') ?></div><div class="l">Students taught</div></div>
      <div class="cg"><div class="n"><?= e($settings['stat_countries'] ?? '12') ?></div><div class="l">Countries reached</div></div>
      <div class="cg"><div class="n"><em><?= e($settings['stat_grades'] ?? '+1.8') ?></em></div><div class="l">Avg. grade bands gained</div></div>
      <div class="cg"><div class="n"><?= e($settings['stat_curricula'] ?? '4') ?></div><div class="l">Curricula covered</div></div>
    </div>
  </div>
</section>

<!-- ===================== HOW IT WORKS ===================== -->
<section>
  <div class="wrap">
    <div class="section-head center">
      <span class="pill-eyebrow"><?= e(block('home.steps.eyebrow')) ?></span>
      <h2><?= e(block('home.steps.title')) ?></h2>
    </div>
    <ol class="steps">
      <li><h4>Choose curriculum &amp; subject</h4><p>Tell us your board, level and subject — IB, IGCSE or A Level, Maths or Physics.</p></li>
      <li><h4>Pick 1-to-1 or small group</h4><p>Personal pace, or a small cohort of up to six students at a similar level.</p></li>
      <li><h4>Book your slot</h4><p>Submit your requirement or book a free consultation call directly.</p></li>
      <li><h4>Join on Zoom</h4><p>Get your confirmation and class link, then start working through real problems.</p></li>
    </ol>
  </div>
</section>

<!-- ===================== WORKED EXAMPLE ===================== -->
<section class="section alt">
  <div class="wrap split">
    <div class="split-copy">
      <span class="pill-eyebrow"><?= e(block('home.worked.eyebrow')) ?></span>
      <h2><?= e(block('home.worked.title')) ?></h2>
      <p><?= e(block('home.worked.intro')) ?></p>
      <div style="margin-top:8px;">
        <a href="<?= e(base_url('book')) ?>" class="btn btn-primary">Try a free consultation</a>
      </div>
    </div>
    <div class="split-art">
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
  </div>
</section>

<!-- ===================== TESTIMONIALS ===================== -->
<section>
  <div class="wrap">
    <div class="section-head center">
      <span class="pill-eyebrow"><?= e(block('home.testi.eyebrow')) ?></span>
      <h2><?= e(block('home.testi.title')) ?></h2>
    </div>
    <div class="testi-grid">
      <div class="testi-card">
        <div class="quote-mark">&rdquo;</div>
        <div class="stars">★★★★★</div>
        <p>My daughter went from dreading Physics past papers to actually asking for extra ones. Her tutor teaches the Cambridge mark scheme, not just the topic.</p>
        <div class="testi-who"><div class="av">RA</div><div><div class="nm">Parent of an AS &amp; A Level student</div><div class="rl">Dubai, UAE</div></div></div>
      </div>
      <div class="testi-card">
        <div class="quote-mark">&rdquo;</div>
        <div class="stars">★★★★★</div>
        <p>The small-group Maths class was perfect — six students, all at my level, and the tutor still knew exactly where each of us was struggling. Jumped two grade bands.</p>
        <div class="testi-who"><div class="av">SO</div><div><div class="nm">IB Diploma student</div><div class="rl">Singapore</div></div></div>
      </div>
      <div class="testi-card">
        <div class="quote-mark">&rdquo;</div>
        <div class="stars">★★★★★</div>
        <p>Booking, the Zoom link, the recordings — all seamless across our timezone. Finally a tutor who understood the IGCSE spec properly.</p>
        <div class="testi-who"><div class="av">MN</div><div><div class="nm">Parent of an IGCSE student</div><div class="rl">Bengaluru, India</div></div></div>
      </div>
    </div>
  </div>
</section>

<!-- ===================== RESOURCES ===================== -->
<?php if (!empty($posts)): ?>
<section class="section alt">
  <div class="wrap">
    <div class="section-head center">
      <span class="pill-eyebrow"><?= e(block('home.resources.eyebrow')) ?></span>
      <h2><?= e(block('home.resources.title')) ?></h2>
    </div>
    <div class="post-grid">
      <?php foreach ($posts as $p): ?>
        <a class="post-card" href="<?= e(base_url('resources/' . $p['slug'])) ?>">
          <div class="post-thumb"><span class="kico"><?= $ic['book'] ?></span></div>
          <div class="post-body">
            <?php if (!empty($p['kicker'])): ?><div class="kicker"><?= e($p['kicker']) ?></div><?php endif; ?>
            <h3><?= e($p['title']) ?></h3>
            <p><?= e(mb_strimwidth($p['excerpt'] ?? '', 0, 110, '…')) ?></p>
            <div class="post-meta"><span>Read article →</span><span><?= e($p['published_at'] ? date('j M Y', strtotime($p['published_at'])) : '') ?></span></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ===================== CTA BANNER ===================== -->
<section>
  <div class="wrap">
    <div class="cta-banner">
      <div class="cta-copy">
        <h2><?= e(block('home.cta.title')) ?></h2>
        <p><?= e(block('home.cta.body')) ?></p>
      </div>
      <div style="position:relative;z-index:1;">
        <a href="<?= e(base_url('book')) ?>" class="btn btn-primary">Book a Free Consultation</a>
      </div>
    </div>
  </div>
</section>
