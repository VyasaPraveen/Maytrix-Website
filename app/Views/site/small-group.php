<?php /** @var array $batches */
$curricClass = ['ib_dp' => 'c-ib', 'ib_myp' => 'c-ib', 'igcse' => 'c-igcse', 'alevel' => 'c-alevel'];
?>
<section>
  <div class="wrap">
    <span class="eyebrow">Small-Group Classes</span>
    <h1 style="font-size:32px;">Learn alongside students at your level</h1>
    <p style="max-width:640px;margin-bottom:36px;">Cohorts capped at 6 students, matched by curriculum and level, so pacing stays personal even in a group. Seats update live as students enrol.</p>

    <?php if (empty($batches)): ?>
      <div class="card"><p style="margin:0;">No open batches right now. <a href="<?= e(base_url('book')) ?>" class="btn-ghost">Book a consultation →</a> and we'll place you in the next cohort.</p></div>
    <?php endif; ?>

    <?php foreach ($batches as $b):
        $max = (int) $b['max_seats'];
        $avail = (int) $b['available_seats'];
        $taken = $max - $avail;
        $fill = $max > 0 ? round($taken / $max * 100) : 0;
        $barClass = $avail <= 1 ? 'high' : ($avail <= floor($max / 2) ? 'mid' : '');
        $cclass = $curricClass[$b['curriculum_code']] ?? 'c-ib';
        $modeCode = $b['mode_code'] ?? 'online';
    ?>
      <div class="class-card <?= e($cclass) ?>">
        <div class="meta">
          <div class="name"><?= e($b['name']) ?>
            <span class="mode-badge mode-<?= e($modeCode) ?>"><?= e($b['mode_name'] ?? ucfirst($modeCode)) ?></span>
          </div>
          <div class="sub">Starts <?= e($b['start_date'] ? date('j M Y', strtotime($b['start_date'])) : 'TBC') ?><?= $b['tutor_name'] ? ' · Tutor: ' . e($b['tutor_name']) : '' ?></div>
        </div>
        <div class="field"><div class="k">Schedule</div><div class="v"><?= e($b['schedule_text'] ?? 'TBC') ?></div></div>
        <div class="field"><div class="k">Duration</div><div class="v"><?= e($b['duration_text'] ?? 'TBC') ?></div></div>
        <div class="field">
          <div class="k">Seats</div>
          <div class="v"><?= $avail ?> / <?= $max ?> left</div>
          <div class="seats-bar"><i class="<?= e($barClass) ?>" style="width:<?= $fill ?>%;"></i></div>
        </div>
        <?php if ($avail > 0): ?>
          <a href="<?= e(base_url('batch/' . $b['id'])) ?>" class="btn btn-outline btn-sm">Enrol</a>
        <?php else: ?>
          <span class="badge-full">Full</span>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>

    <p class="notice-inline" style="margin-top:8px;">Seat bars: teal = plenty of room, amber = filling up, rust = nearly full. Available seats = maximum seats − confirmed enrolments, updated automatically.</p>

    <div class="card" style="margin-top:30px;">
      <span class="tag">How enrolment works</span>
      <ol style="counter-reset:m;margin-top:12px;">
        <li style="padding:9px 0;border-bottom:1px solid var(--line);">Admin creates the course — start date, schedule, duration, seat cap, mode (online / offline)</li>
        <li style="padding:9px 0;border-bottom:1px solid var(--line);">Student enrols and completes payment</li>
        <li style="padding:9px 0;border-bottom:1px solid var(--line);">Enrolment confirmation sent</li>
        <li style="padding:9px 0;">Zoom link (for online) or venue details, added by our team once scheduled, shared automatically with the full class ahead of the first session</li>
      </ol>
    </div>
  </div>
</section>
