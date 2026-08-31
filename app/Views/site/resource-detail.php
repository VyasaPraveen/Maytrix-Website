<?php /** @var array $post */ ?>
<section>
  <div class="wrap" style="max-width:720px;">
    <a href="<?= e(base_url('resources')) ?>" class="btn-ghost" style="font-size:12.5px;display:inline-block;">← Back to Resources</a>
    <div class="kicker" style="font-family:'IBM Plex Mono',monospace;font-size:11px;letter-spacing:.06em;text-transform:uppercase;color:var(--brass-dark);margin:18px 0 10px;"><?= e($post['kicker'] ?? '') ?></div>
    <h1 style="font-size:30px;"><?= e($post['title']) ?></h1>
    <?php if (!empty($post['published_at'])): ?>
      <p class="muted"><?= e(date('j M Y', strtotime($post['published_at']))) ?></p>
    <?php endif; ?>
    <div class="content-body" style="font-size:16px;margin-top:20px;">
      <?= $post['body_html'] ?? ('<p>' . e($post['excerpt'] ?? '') . '</p>') ?>
    </div>
    <div style="margin-top:40px;">
      <a href="<?= e(base_url('book')) ?>" class="btn btn-primary">Book a Free Consultation</a>
    </div>
  </div>
</section>
<?php
$articleLd = array_filter([
  '@context' => 'https://schema.org',
  '@type'    => 'BlogPosting',
  'headline' => $post['title'],
  'description' => $post['seo_description'] ?: ($post['excerpt'] ?? ''),
  'url'      => base_url('resources/' . $post['slug']),
  'mainEntityOfPage' => base_url('resources/' . $post['slug']),
  'image'    => asset('img/logo@2x.png'),
  'datePublished' => !empty($post['published_at']) ? date('c', strtotime($post['published_at'])) : null,
  'dateModified'  => !empty($post['updated_at']) ? date('c', strtotime($post['updated_at'])) : (!empty($post['published_at']) ? date('c', strtotime($post['published_at'])) : null),
  'author'   => ['@type' => 'Organization', 'name' => $settings['brand_name'] ?? 'Maytrix Education'],
  'publisher' => [
    '@type' => 'Organization',
    'name'  => $settings['brand_name'] ?? 'Maytrix Education',
    'logo'  => ['@type' => 'ImageObject', 'url' => asset('img/logo@2x.png')],
  ],
], fn($v) => $v !== null && $v !== '');
?>
<script type="application/ld+json" nonce="<?= e(csp_nonce()) ?>"><?= json_encode($articleLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
