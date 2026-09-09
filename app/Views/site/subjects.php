<?php /** @var array $subjects @var array $topicsBySubject @var array $pages */
$pagesBySubject = [];
foreach ($pages as $p) { $pagesBySubject[$p['subject_id']][] = $p; }
?>
<section class="page-hero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= e(base_url()) ?>">Home</a><span>›</span> Subjects</div>
    <span class="pill-eyebrow"><?= e(block('pages.subjects.eyebrow')) ?></span>
    <h1><?= e(block('pages.subjects.title')) ?></h1>
    <p><?= e(block('pages.subjects.intro')) ?></p>
  </div>
</section>
<section>
  <div class="wrap">
    <div class="tabbar" id="subjectTabs">
      <?php foreach ($subjects as $i => $s): ?>
        <button type="button" class="<?= $i === 0 ? 'active' : '' ?>" data-tab="s<?= (int)$s['id'] ?>"><?= e($s['name']) ?></button>
      <?php endforeach; ?>
    </div>

    <?php foreach ($subjects as $i => $s): ?>
      <div class="tabpanel <?= $i === 0 ? 'active' : '' ?>" data-panel="s<?= (int)$s['id'] ?>">
        <div class="topic-grid">
          <?php foreach (($topicsBySubject[$s['id']] ?? []) as $t): ?>
            <div class="topic">
              <span class="k"><?= e($t['key_label'] ?? 'CORE') ?></span>
              <h4><?= e($t['title']) ?></h4>
              <p><?= e($t['description'] ?? '') ?></p>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="card" style="margin-top:26px;">
          <span class="tag"><?= e($s['name']) ?> — levels by curriculum</span>
          <div class="level-grid" style="margin-top:16px;">
            <?php foreach (($pagesBySubject[$s['id']] ?? []) as $p):
                $curLabel = $p['eyebrow'] ? explode(' · ', $p['eyebrow'])[0] : $p['title'];
                $levels = is_array($p['badges'] ?? null) ? $p['badges'] : [];
            ?>
              <a href="<?= e(base_url('curriculum/' . $p['slug'])) ?>" class="level-item">
                <div class="level-head"><span class="level-curric"><?= e($curLabel) ?></span><span class="level-go" aria-hidden="true">→</span></div>
                <?php if ($levels): ?>
                  <div class="level-badges">
                    <?php foreach ($levels as $b): ?><span><?= e($b) ?></span><?php endforeach; ?>
                  </div>
                <?php else: ?>
                  <div class="level-badges muted-badges"><span>Levels on request</span></div>
                <?php endif; ?>
              </a>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <div style="text-align:center;margin-top:44px;">
      <a href="<?= e(base_url('book')) ?>" class="btn btn-primary">Book a Free Consultation</a>
    </div>
  </div>
</section>
