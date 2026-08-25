<?php /** @var array $curricula @var array $pages */
// Group curriculum pages by curriculum id.
$pagesByCurric = [];
foreach ($pages as $p) { $pagesByCurric[$p['curriculum_id']][] = $p; }
?>
<section class="page-hero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= e(base_url()) ?>">Home</a><span>›</span> Curricula</div>
    <span class="pill-eyebrow">Curricula</span>
    <h1>IB · IB MYP · Cambridge IGCSE · Cambridge AS &amp; A Level</h1>
    <p>Four curricula, taught by specialists who work inside them every week. Select a curriculum to see how classes are structured, or open the dedicated page for each subject.</p>
  </div>
</section>
<section>
  <div class="wrap">
    <div class="tabbar" id="curriculaTabs">
      <?php foreach ($curricula as $i => $c): ?>
        <button type="button" class="<?= $i === 0 ? 'active' : '' ?>" data-tab="c<?= (int)$c['id'] ?>"><?= e($c['short_name'] ?: $c['name']) ?></button>
      <?php endforeach; ?>
    </div>

    <?php foreach ($curricula as $i => $c): ?>
      <div class="tabpanel <?= $i === 0 ? 'active' : '' ?>" data-panel="c<?= (int)$c['id'] ?>">
        <div class="grid-2">
          <div>
            <h3><?= e($c['name']) ?></h3>
            <p><?= e($c['description'] ?? $c['tagline'] ?? '') ?></p>
          </div>
          <div class="card">
            <span class="tag">Dedicated pages</span>
            <ul style="margin-top:10px;">
              <?php $cp = $pagesByCurric[$c['id']] ?? []; foreach ($cp as $k => $p): ?>
                <li style="padding:8px 0;<?= $k < count($cp)-1 ? 'border-bottom:1px solid var(--line);' : '' ?>">
                  <a href="<?= e(base_url('curriculum/' . $p['slug'])) ?>" class="btn-ghost"><?= e($p['title']) ?> →</a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <div style="text-align:center;margin-top:44px;">
      <a href="<?= e(base_url('book')) ?>" class="btn btn-primary">Book a Free Consultation</a>
    </div>
  </div>
</section>
