<?php /** @var array $matrix */
// Build curriculum → subject → page lookup.
$grid = [];      // curriculum_name => [subject_code => page]
$curricOrder = [];
$subjectCols = []; // subject_code => subject_name
foreach ($matrix as $p) {
    $grid[$p['curriculum_name']][$p['subject_code']] = $p;
    $curricOrder[$p['curriculum_name']] = true;
    $subjectCols[$p['subject_code']] = $p['subject_name'];
}
?>
<section class="page-hero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= e(base_url()) ?>">Home</a><span>›</span> Courses</div>
    <span class="pill-eyebrow">Courses / Programmes</span>
    <h1>The Course Matrix</h1>
    <p>Every curriculum, crossed with every subject we teach. Click any cell to open that dedicated page. Every combination is available as both 1-to-1 and Small-Group.</p>
  </div>
</section>
<section>
  <div class="wrap">
    <table class="course-matrix">
      <thead>
        <tr>
          <th>Curriculum \ Subject</th>
          <?php foreach ($subjectCols as $name): ?>
            <th class="avail"><?= e($name) ?></th>
          <?php endforeach; ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach (array_keys($curricOrder) as $curricName): ?>
          <tr>
            <td><b><?= e($curricName) ?></b></td>
            <?php foreach (array_keys($subjectCols) as $sCode): ?>
              <td class="avail">
                <?php if (!empty($grid[$curricName][$sCode])): $pg = $grid[$curricName][$sCode]; ?>
                  <a href="<?= e(base_url('curriculum/' . $pg['slug'])) ?>" class="pill">View page →</a>
                <?php else: ?>
                  <span class="muted">—</span>
                <?php endif; ?>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div style="text-align:center;margin-top:44px;">
      <a href="<?= e(base_url('book')) ?>" class="btn btn-primary">Book a Free Consultation</a>
    </div>
  </div>
</section>
