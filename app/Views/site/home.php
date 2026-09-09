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
      <span>IBDP</span><span>IBMYP</span><span>Cambridge IGCSE</span><span>Cambridge AS &amp; A Level</span>
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
    <div class="marquee-item">Mathematics <span>✦</span> Physics <span>✦</span> IBDP <span>✦</span> IBMYP <span>✦</span> Cambridge IGCSE <span>✦</span> AS &amp; A Level <span>✦</span> Past-paper mastery <span>✦</span></div>
    <div class="marquee-item">Mathematics <span>✦</span> Physics <span>✦</span> IBDP <span>✦</span> IBMYP <span>✦</span> Cambridge IGCSE <span>✦</span> AS &amp; A Level <span>✦</span> Past-paper mastery <span>✦</span></div>
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
      <?php foreach ($curricula as $c):
          // Full abbreviation badge (no truncation). MYP shows its year band explicitly.
          $abbr = $c['short_name'] ?: strtoupper((string)($c['code'] ?? ''));
          if (($c['code'] ?? '') === 'ib_myp') { $abbr = 'IBMYP Year 4 & 5'; }
      ?>
        <div class="curr-card">
          <div class="badge-ico"><?= e($abbr) ?></div>
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

<!-- ===================== GRAPHS ===================== -->
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
      <div class="worked-panel graph-card">
        <div class="worked-head">
          <span class="label">Interactive graph</span>
          <div class="subject-tabs">
            <button type="button" class="active" data-graph="math">Maths</button>
            <button type="button" data-graph="physics">Physics</button>
          </div>
        </div>
        <div class="worked-body">
          <!-- Maths: quadratic function -->
          <figure class="graph-panel active" data-graph-panel="math">
            <svg class="mx-graph" viewBox="0 0 480 290" role="img" aria-label="Graph of the quadratic function y = x squared">
              <defs>
                <linearGradient id="mxGmath" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0" stop-color="#0E9EE6" stop-opacity=".30"/>
                  <stop offset="1" stop-color="#0E9EE6" stop-opacity="0"/>
                </linearGradient>
                <marker id="mxArrowA" markerWidth="9" markerHeight="9" refX="5" refY="4.5" orient="auto"><path d="M0 0 L7 4.5 L0 9 Z" fill="#9DB0C9"/></marker>
              </defs>
              <g stroke="#E4EDF6" stroke-width="1">
                <line x1="130" y1="34" x2="130" y2="250"/><line x1="190" y1="34" x2="190" y2="250"/>
                <line x1="310" y1="34" x2="310" y2="250"/><line x1="370" y1="34" x2="370" y2="250"/>
                <line x1="45" y1="90" x2="452" y2="90"/><line x1="45" y1="170" x2="452" y2="170"/>
              </g>
              <path d="M110 55 Q250 300 390 55 L390 250 L110 250 Z" fill="url(#mxGmath)"/>
              <path d="M110 55 Q250 300 390 55" fill="none" stroke="#0E9EE6" stroke-width="3.5" stroke-linecap="round"/>
              <line x1="250" y1="258" x2="250" y2="30" stroke="#9DB0C9" stroke-width="1.6" marker-end="url(#mxArrowA)"/>
              <line x1="42" y1="250" x2="458" y2="250" stroke="#9DB0C9" stroke-width="1.6" marker-end="url(#mxArrowA)"/>
              <circle cx="250" cy="178" r="5.5" fill="#0A2452"/>
              <circle cx="329" cy="118" r="5" fill="#0E9EE6"/>
              <text x="440" y="243" font-size="13" fill="#44566E" font-family="'IBM Plex Mono',monospace">x</text>
              <text x="258" y="46" font-size="13" fill="#44566E" font-family="'IBM Plex Mono',monospace">y</text>
              <text x="300" y="96" font-size="14" fill="#0A2452" font-family="'IBM Plex Mono',monospace" font-weight="600">y = x²</text>
            </svg>
            <figcaption>Quadratic function — the shape behind projectile paths, optimisation and area problems.</figcaption>
          </figure>
          <!-- Physics: simple harmonic motion -->
          <figure class="graph-panel" data-graph-panel="physics">
            <svg class="mx-graph" viewBox="0 0 480 290" role="img" aria-label="Simple harmonic motion — displacement versus time">
              <defs>
                <marker id="mxArrowB" markerWidth="9" markerHeight="9" refX="5" refY="4.5" orient="auto"><path d="M0 0 L7 4.5 L0 9 Z" fill="#9DB0C9"/></marker>
              </defs>
              <g stroke="#E4EDF6" stroke-width="1">
                <line x1="115" y1="34" x2="115" y2="250"/><line x1="205" y1="34" x2="205" y2="250"/>
                <line x1="295" y1="34" x2="295" y2="250"/><line x1="385" y1="34" x2="385" y2="250"/>
                <line x1="45" y1="82" x2="452" y2="82"/><line x1="45" y1="218" x2="452" y2="218"/>
              </g>
              <line x1="115" y1="82" x2="360" y2="82" stroke="#BBD3E9" stroke-width="1.2" stroke-dasharray="4 5"/>
              <path d="M70 150 C100 59 130 59 160 150 C190 241 220 241 250 150 C280 59 310 59 340 150 C370 241 400 241 430 150"
                    fill="none" stroke="#1E63A8" stroke-width="3.5" stroke-linecap="round"/>
              <line x1="60" y1="258" x2="60" y2="30" stroke="#9DB0C9" stroke-width="1.6" marker-end="url(#mxArrowB)"/>
              <line x1="52" y1="150" x2="458" y2="150" stroke="#9DB0C9" stroke-width="1.6" marker-end="url(#mxArrowB)"/>
              <circle cx="115" cy="82" r="5.5" fill="#0A2452"/>
              <circle cx="205" cy="218" r="5" fill="#0E9EE6"/>
              <text x="440" y="168" font-size="13" fill="#44566E" font-family="'IBM Plex Mono',monospace">t</text>
              <text x="68" y="46" font-size="13" fill="#44566E" font-family="'IBM Plex Mono',monospace">x</text>
              <text x="300" y="70" font-size="13.5" fill="#0A2452" font-family="'IBM Plex Mono',monospace" font-weight="600">x = A sin(ωt)</text>
            </svg>
            <figcaption>Simple harmonic motion — the wave behind sound, light, springs and oscillations.</figcaption>
          </figure>
        </div>
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
        <div class="testi-who"><div class="av">SO</div><div><div class="nm">IBDP student</div><div class="rl">Singapore</div></div></div>
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

<!-- ===================== FAQ ===================== -->
<?php
$faqItems = [];
for ($i = 1; $i <= 6; $i++) {
    $fq = trim((string) block('faq.q' . $i));
    $fa = trim((string) block('faq.a' . $i));
    if ($fq !== '' && $fa !== '') { $faqItems[] = ['q' => $fq, 'a' => $fa]; }
}
?>
<?php if ($faqItems): ?>
<section class="section alt">
  <div class="wrap wrap-narrow">
    <div class="section-head center">
      <span class="pill-eyebrow"><?= e(block('faq.eyebrow')) ?></span>
      <h2><?= e(block('faq.title')) ?></h2>
    </div>
    <div class="faq-list">
      <?php foreach ($faqItems as $k => $f): ?>
        <details class="faq-item"<?= $k === 0 ? ' open' : '' ?>>
          <summary><span class="faq-q"><?= e($f['q']) ?></span><span class="faq-ico" aria-hidden="true"></span></summary>
          <div class="faq-a"><p><?= e($f['a']) ?></p></div>
        </details>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<script type="application/ld+json" nonce="<?= e(csp_nonce()) ?>"><?= json_encode([
  '@context'   => 'https://schema.org',
  '@type'      => 'FAQPage',
  'mainEntity' => array_map(fn($f) => [
      '@type'          => 'Question',
      'name'           => $f['q'],
      'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
  ], $faqItems),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
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
