<?php
/** @var array $authUser @var array $flash @var array $errors @var string $content
 *  @var string $currentPath @var int $pendingBookings @var int $unreadMessages @var string $activeMenu */
$menu = [
    'Overview' => [
        ['', 'Dashboard', 'dashboard'],
        ['reports', 'Reports', 'reports'],
    ],
    'Enrolments & Bookings' => [
        ['bookings', '1-to-1 Bookings', 'bookings', $pendingBookings],
        ['batches', 'Batches', 'batches'],
        ['enrolments', 'Enrolments', 'enrolments'],
        ['students', 'Students', 'students'],
    ],
    'Catalog' => [
        ['curriculum-pages', 'Curriculum Pages', 'curriculum-pages'],
        ['courses', 'Courses', 'courses'],
        ['curricula', 'Curricula', 'curricula'],
        ['subjects', 'Subjects', 'subjects'],
        ['topics', 'Topics', 'topics'],
        ['modes', 'Modes', 'modes'],
    ],
    'Content' => [
        ['content', 'Page Content', 'content'],
        ['posts', 'Blog / Resources', 'posts'],
        ['pages', 'Pages', 'pages'],
        ['messages', 'Contact Messages', 'messages', $unreadMessages],
    ],
    'Settings' => [
        ['settings', 'Site Settings', 'settings'],
        ['admins', 'Admin Users', 'admins'],
        ['backups', 'Backups', 'backups'],
    ],
];
$initials = strtoupper(mb_substr($authUser['name'] ?? 'A', 0, 1));
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle ?? 'Dashboard') ?> — Maytrix Admin</title>
<meta name="robots" content="noindex,nofollow">
<link rel="icon" type="image/png" sizes="32x32" href="<?= e(asset('img/favicon-32.png')) ?>">
<link rel="apple-touch-icon" href="<?= e(asset('img/apple-touch-icon.png')) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,400;8..60,600;8..60,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(admin_asset('css/admin.css')) ?>">
</head>
<body>
<div class="admin">
  <aside class="sidebar" id="sidebar">
    <div class="logo"><img src="<?= e(asset('img/logo-white.png')) ?>" alt="Maytrix Education" class="logo-img" width="360" height="130"></div>
    <nav>
      <?php foreach ($menu as $group => $items): ?>
        <div class="nav-group"><?= e($group) ?></div>
        <?php foreach ($items as $item):
            [$path, $label, $key] = $item;
            $badge = $item[3] ?? 0;
            $active = ($activeMenu === $key) ? ' active' : '';
        ?>
          <a class="item<?= $active ?>" href="<?= e(admin_url($path)) ?>">
            <?= e($label) ?>
            <?php if ($badge > 0): ?><span class="badge"><?= (int)$badge ?></span><?php endif; ?>
          </a>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </nav>
    <div class="foot">
      <a href="<?= e(base_url()) ?>" target="_blank" style="color:#9aa4c4;">View website ↗</a>
    </div>
  </aside>

  <div class="main">
    <div class="topbar">
      <div style="display:flex;align-items:center;gap:14px;">
        <button class="menu-toggle" id="menuToggle" aria-label="Menu">☰</button>
        <h1><?= e($pageTitle ?? 'Dashboard') ?></h1>
      </div>
      <div class="user">
        <div class="avatar"><?= e($initials) ?></div>
        <div>
          <div style="font-weight:600;color:var(--ink);"><?= e($authUser['name'] ?? 'Admin') ?></div>
          <form method="post" action="<?= e(admin_url('logout')) ?>" style="margin:0;">
            <?= \App\Core\Csrf::field() ?>
            <button type="submit" style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:12.5px;padding:6px 2px;min-height:32px;">Sign out</button>
          </form>
        </div>
      </div>
    </div>

    <div class="content">
      <?php if (!empty($flash['success'])): ?><div class="alert alert-success"><?= e($flash['success']) ?></div><?php endif; ?>
      <?php if (!empty($flash['error'])): ?><div class="alert alert-error"><?= e($flash['error']) ?></div><?php endif; ?>
      <?= $content ?>
    </div>
  </div>
</div>
<script src="<?= e(admin_asset('js/admin.js')) ?>"></script>
</body>
</html>
