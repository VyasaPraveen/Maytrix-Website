<?php /** @var array $page */
$badges = $page['badges'] ?? [];
$topics = $page['topics'] ?? [];
?>
<section>
  <div class="wrap" style="max-width:760px;">
    <a href="<?= e(base_url('programmes')) ?>" class="btn-ghost" style="font-size:12.5px;display:inline-block;">← Back to Course Matrix</a>
    <span class="eyebrow" style="margin-top:18px;display:block;"><?= e($page['eyebrow'] ?? '') ?></span>
    <h1 style="font-size:32px;"><?= e($page['title']) ?></h1>
    <div class="subjects" style="margin-bottom:20px;">
      <?php foreach ($badges as $b): ?><span><?= e($b) ?></span><?php endforeach; ?>
    </div>
    <p style="font-size:17px;"><?= e($page['intro'] ?? '') ?></p>

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
      <div class="hero-ctas" style="margin-top:6px;">
        <a href="<?= e(base_url('book?curriculum_id=' . $page['curriculum_id'] . '&subject_id=' . $page['subject_id'])) ?>" class="btn btn-primary btn-sm">Book a Free Consultation</a>
        <a href="<?= e(base_url('small-group')) ?>" class="btn btn-outline btn-sm">See Small-Group Batches</a>
      </div>
    </div>
  </div>
</section>
