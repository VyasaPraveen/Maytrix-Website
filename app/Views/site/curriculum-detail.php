<?php /** @var array $page */
$badges = $page['badges'] ?? [];
$topics = $page['topics'] ?? [];
?>
<section class="page-hero page-hero--plain">
  <div class="wrap" style="max-width:820px;">
    <div class="crumbs"><a href="<?= e(base_url()) ?>">Home</a><span>›</span> <a href="<?= e(base_url('programmes')) ?>">Courses</a><span>›</span> <?= e($page['title']) ?></div>
    <?php if (!empty($page['eyebrow'])): ?><span class="pill-eyebrow"><?= e($page['eyebrow']) ?></span><?php endif; ?>
    <h1><?= e($page['title']) ?></h1>
    <div class="subjects" style="margin-bottom:18px;">
      <?php foreach ($badges as $b): ?><span><?= e($b) ?></span><?php endforeach; ?>
    </div>
    <p style="font-size:17px;"><?= e($page['intro'] ?? '') ?></p>
  </div>
</section>
<section>
  <div class="wrap" style="max-width:820px;">
    <?php if (!empty($page['body_html'])): ?>
      <div class="content-body"><?= $page['body_html'] ?></div>
    <?php endif; ?>

    <div class="card" style="margin-top:10px;">
      <span class="tag">What we cover</span>
      <ul style="margin-top:12px;">
        <?php foreach ($topics as $i => $t): $last = $i === count($topics) - 1; ?>
          <li style="padding:10px 0;<?= $last ? '' : 'border-bottom:1px solid var(--line);' ?>">
            <b style="font-size:14.5px;"><?= e($t[0] ?? '') ?></b><br>
            <span style="font-size:13.5px;color:var(--ink-soft);"><?= e($t[1] ?? '') ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="card" style="margin-top:20px;">
      <span class="tag">Available as</span>
      <p style="margin-top:10px;font-size:14.5px;">1-to-1 Classes and Small-Group Classes — both bookable from this page.</p>
      <div class="hero-ctas cta-row-2" style="margin-top:6px;">
        <a href="<?= e(base_url('book?curriculum_id=' . $page['curriculum_id'] . '&subject_id=' . $page['subject_id'])) ?>" class="btn btn-primary btn-sm">Book a Free Consultation</a>
        <a href="<?= e(base_url('small-group')) ?>" class="btn btn-outline btn-sm">See Small-Group Batches</a>
      </div>
    </div>
  </div>
</section>
<?php
$ldFlags = JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
$courseLd = [
  '@context' => 'https://schema.org',
  '@type'    => 'Course',
  'name'     => $page['title'],
  'description' => $page['seo_description'] ?: ($page['intro'] ?? ''),
  'url'      => base_url('curriculum/' . $page['slug']),
  'image'    => asset('img/logo@2x.png'),
  'provider' => [
    '@type' => 'EducationalOrganization',
    'name'  => $settings['brand_name'] ?? 'Maytrix Education',
    'url'   => base_url(),
  ],
  'offers' => [
    '@type' => 'Offer',
    'category' => 'Free consultation',
    'price' => '0',
    'priceCurrency' => 'USD',
    'availability' => 'https://schema.org/InStock',
    'url' => base_url('book'),
  ],
  'hasCourseInstance' => [
    '@type' => 'CourseInstance',
    'courseMode' => 'online',
    'courseWorkload' => 'PT2H',
  ],
];
$breadcrumbLd = [
  '@context' => 'https://schema.org',
  '@type'    => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => base_url()],
    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Courses', 'item' => base_url('programmes')],
    ['@type' => 'ListItem', 'position' => 3, 'name' => $page['title'], 'item' => base_url('curriculum/' . $page['slug'])],
  ],
];
?>
<script type="application/ld+json" nonce="<?= e(csp_nonce()) ?>"><?= json_encode($courseLd, $ldFlags) ?></script>
<script type="application/ld+json" nonce="<?= e(csp_nonce()) ?>"><?= json_encode($breadcrumbLd, $ldFlags) ?></script>
