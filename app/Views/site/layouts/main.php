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
<meta property="og:title" content="<?= e($metaTitle ?? $brand) ?>">
<meta property="og:description" content="<?= e($metaDescription ?? '') ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?= e($brand) ?>">
<link rel="icon" type="image/png" sizes="32x32" href="<?= e(asset('img/favicon-32.png')) ?>">
<link rel="icon" type="image/png" sizes="16x16" href="<?= e(asset('img/favicon-16.png')) ?>">
<link rel="apple-touch-icon" href="<?= e(asset('img/apple-touch-icon.png')) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,400;8..60,500;8..60,600;8..60,700&family=Inter:wght@400;500;600;700;800&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(asset('css/site.css')) ?>">
<?php if ($gaId): ?>
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($gaId) ?>"></script>
<script nonce="<?= e(csp_nonce()) ?>">window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','<?= e($gaId) ?>');</script>
<?php endif; ?>
</head>
<body>

<header class="site">
  <div class="nav-row">
    <a href="<?= e(base_url()) ?>" class="brand" aria-label="<?= e($brand) ?> — home">
      <img src="<?= e(asset('img/logo.png')) ?>" alt="<?= e($brand) ?>" class="brand-logo" width="600" height="217">
    </a>
    <nav class="primary" id="primaryNav">
      <a href="<?= e(base_url()) ?>" class="<?= trim($isActive('home')) ?>">Home</a>
      <a href="<?= e(base_url('about')) ?>" class="<?= trim($isActive('about')) ?>">About</a>

      <div class="dropdown" data-dropdown>
        <button type="button" aria-expanded="false" class="<?= trim($isActive('programmes')) ?>">Programmes <span class="chev"></span></button>
        <div class="dropdown-menu">
          <a href="<?= e(base_url('curricula')) ?>">Curricula<small>IB · IB MYP · IGCSE · A Level</small></a>
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
      <a href="<?= e(base_url('book')) ?>" class="btn btn-primary btn-sm">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4.5" width="18" height="16" rx="2"/><path d="M3 9.5h18M8 3v3M16 3v3"/></svg>
        Book a Consultation
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
      <img src="<?= e(asset('img/logo-white.png')) ?>" alt="<?= e($brand) ?>" class="foot-logo" width="600" height="217">
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
    <span>Built by EPIXS Media</span>
  </div>
</footer>

<script src="<?= e(asset('js/site.js')) ?>"></script>
</body>
</html>
