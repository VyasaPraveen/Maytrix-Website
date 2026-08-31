<?php /** @var array $groups */ ?>
<div class="panel">
  <div class="panel-head">
    <h3>Editable page content</h3>
  </div>
  <div class="panel-body">
    <p class="muted" style="margin:0 0 18px;">
      Edit the headings, intros and highlight cards that appear on the public site.
      Leaving a field blank restores its original wording. Live data (courses, batches,
      blog posts) and page forms are managed from their own menus.
    </p>
    <div class="stat-grid">
      <?php foreach ($groups as $key => $g): ?>
        <a class="content-group-card" href="<?= e(admin_url('content/' . $key . '/edit')) ?>">
          <div class="cg-title"><?= e($g['label']) ?></div>
          <div class="cg-meta"><?= (int) $g['count'] ?> editable blocks<?php if ($g['edited'] > 0): ?> · <span class="cg-edited"><?= (int) $g['edited'] ?> customised</span><?php endif; ?></div>
          <span class="cg-go">Edit content →</span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>
