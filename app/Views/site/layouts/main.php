<?php
/** @var array $settings @var array $navCurricula @var array $navSubjects @var array $navPages */
/** @var array $flash @var array $errors @var string $content @var string $activeNav */
$brand = $settings['brand_name'] ?? 'Maytrix Education';
$gaId  = $settings['ga_measurement_id'] ?? '';
if ($gaId === '') { $gaId = (string) config('analytics.ga_id', ''); }
$isActive = fn(string $key) => ($activeNav ?? '') === $key ? ' active' : '';
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($metaTitle ?? $brand) ?></title>
<meta name="description" content="<?= e($metaDescription ?? '') ?>">
<link rel="canonical" href="<?= e(base_url(ltrim($currentPath, '/'))) ?>">
<meta name="robots" content="<?= e($metaRobots ?? 'index,follow,max-image-preview:large') ?>">
<meta name="theme-color" content="#0A2452">
<meta property="og:title" content="<?= e($metaTitle ?? $brand) ?>">
<meta property="og:description" content="<?= e($metaDescription ?? '') ?>">
<meta property="og:type" content="website">
<meta property="og:url" content="<?= e(base_url(ltrim($currentPath, '/'))) ?>">
<meta property="og:site_name" content="<?= e($brand) ?>">
<meta property="og:image" content="<?= e(asset('img/logo@2x.png')) ?>">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($metaTitle ?? $brand) ?>">
<meta name="twitter:description" content="<?= e($metaDescription ?? '') ?>">
<meta name="twitter:image" content="<?= e(asset('img/logo@2x.png')) ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?= e(asset('img/favicon-32.png')) ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?= e(asset('img/favicon-16.png')) ?>">
<link rel="apple-touch-icon" href="<?= e(asset('img/apple-touch-icon.png')) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,400;8..60,500;8..60,600;8..60,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(asset('css/site.css')) ?>">
<?php if ($gaId): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($gaId) ?>"></script>
<script nonce="<?= e(csp_nonce()) ?>">window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= e($gaId) ?>');</script>
<?php endif; ?>
<?php
$orgLd = [
  '@context' => 'https://schema.org',
  '@type'    => 'EducationalOrganization',
  '@id'      => base_url('#organization'),
  'name'     => $brand,
  'url'      => base_url(),
  'logo'     => asset('img/logo@2x.png'),
  'description' => $settings['footer_tagline'] ?? 'Specialist online Mathematics & Physics tutoring for IB, IBMYP, Cambridge IGCSE and AS & A Level.',
  'email'    => $settings['contact_email'] ?? null,
  'telephone'=> $settings['contact_phone'] ?? null,
  'address'  => ['@type' => 'PostalAddress', 'addressCountry' => 'IN'],
  'sameAs'   => array_values(array_filter([
      $settings['social_facebook'] ?? null,
      $settings['social_instagram'] ?? null,
      $settings['social_linkedin'] ?? null,
  ])),
  'areaServed' => 'Worldwide',
  'knowsAbout' => ['Mathematics', 'Physics', 'IBDP', 'Cambridge IGCSE', 'A Level'],
];
// Drop null / empty values so the emitted entity is clean (no telephone:null, sameAs:[]).
$orgLd = array_filter($orgLd, fn($v) => $v !== null && $v !== '' && $v !== []);
?>
<script type="application/ld+json" nonce="<?= e(csp_nonce()) ?>"><?= json_encode($orgLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
</head>
<body>

<header class="site">
  <div class="nav-row">
    <a href="<?= e(base_url()) ?>" class="brand" aria-label="<?= e($brand) ?> — home">
      <img src="<?= e(asset('img/logo.png')) ?>" alt="<?= e($brand) ?>" class="brand-logo" width="360" height="130">
    </a>
    <nav class="primary" id="primaryNav">
      <a href="<?= e(base_url()) ?>" class="<?= trim($isActive('home')) ?>">Home</a>
      <a href="<?= e(base_url('about')) ?>" class="<?= trim($isActive('about')) ?>">About</a>

      <div class="dropdown" data-dropdown>
        <button type="button" aria-expanded="false" class="<?= trim($isActive('programmes')) ?>">Programmes <span class="chev"></span></button>
        <div class="dropdown-menu">
          <a href="<?= e(base_url('curricula')) ?>">Curricula<small>IB · IBMYP · IGCSE · A Level</small></a>
          <a href="<?= e(base_url('subjects')) ?>">Subjects<small>Mathematics · Physics</small></a>
          <a href="<?= e(base_url('programmes')) ?>">Course Matrix<small>All curriculum × subject pages</small></a>
        </div>
      </div>

      <div class="dropdown" data-dropdown>
        <button type="button" aria-expanded="false" class="<?= trim($isActive('classes')) ?>">Classes <span class="chev"></span></button>
        <div class="dropdown-menu">
          <a href="<?= e(base_url('one-to-one')) ?>">1-to-1 Classes<small>Personal pace</small></a>
          <a href="<?= e(base_url('small-group')) ?>">Small-Group Classes<small>Up to 6 students</small></a>
        </div>
      </div>

      <a href="<?= e(base_url('resources')) ?>" class="<?= trim($isActive('resources')) ?>">Resources</a>
      <a href="<?= e(base_url('contact')) ?>" class="<?= trim($isActive('contact')) ?>">Contact</a>
    </nav>
    <div class="header-actions">
      <a href="<?= e(base_url('book')) ?>" class="btn btn-primary btn-sm header-book">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4.5" width="18" height="16" rx="2"/><path d="M3 9.5h18M8 3v3M16 3v3"/></svg>
        <span class="lbl-full">Book a Consultation</span>
        <span class="lbl-short">Book Now</span>
      </a>
      <button class="hamburger" id="hamburgerBtn" aria-label="Toggle menu"><span></span></button>
    </div>
  </div>
</header>

<main>
  <?php if (!empty($flash['success'])): ?>
    <div class="wrap" style="padding-top:18px;"><div class="alert alert-success"><?= e($flash['success']) ?></div></div>
  <?php endif; ?>
  <?php if (!empty($flash['error'])): ?>
    <div class="wrap" style="padding-top:18px;"><div class="alert alert-error"><?= e($flash['error']) ?></div></div>
  <?php endif; ?>

  <?= $content ?>
</main>

<footer class="site">
  <div class="wrap">
    <div>
      <img src="<?= e(asset('img/logo-white.png')) ?>" alt="<?= e($brand) ?>" class="foot-logo" width="360" height="130" loading="lazy">
      <p style="font-size:13.5px;color:#A9BAD4;max-width:280px;"><?= e($settings['footer_tagline'] ?? '') ?></p>
    </div>
    <div>
      <h4>Curricula</h4>
      <?php foreach ($navCurricula as $c): ?>
        <a href="<?= e(base_url('curricula')) ?>"><?= e($c['name']) ?></a>
      <?php endforeach; ?>
    </div>
    <div>
      <h4>Classes</h4>
      <a href="<?= e(base_url('one-to-one')) ?>">1-to-1 Classes</a>
      <a href="<?= e(base_url('small-group')) ?>">Small-Group Classes</a>
      <a href="<?= e(base_url('programmes')) ?>">Course Matrix</a>
    </div>
    <div>
      <h4>Company</h4>
      <a href="<?= e(base_url('about')) ?>">About <?= e($brand) ?></a>
      <a href="<?= e(base_url('resources')) ?>">Resources / Blog</a>
      <a href="<?= e(base_url('contact')) ?>">Contact</a>
    </div>
  </div>
  <div class="bottom">
    <span>© <?= date('Y') ?> <?= e($brand) ?>. All rights reserved.</span>
    <span>Powered by <a href="https://epixs.in/" target="_blank" rel="noopener">EPIXS Media</a></span>
  </div>
</footer>

<script src="<?= e(asset('js/site.js')) ?>"></script>
</body>
</html>
