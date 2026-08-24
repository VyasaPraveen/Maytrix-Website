<?php /** @var array $batch @var array $errors @var array $old */
use App\Core\Csrf;
$avail = (int) ($batch['available_seats'] ?? 0);
$max = (int) $batch['max_seats'];
$modeCode = $batch['mode_code'] ?? 'online';
?>
<section>
  <div class="wrap" style="max-width:760px;">
    <a href="<?= e(base_url('small-group')) ?>" class="btn-ghost" style="font-size:12.5px;display:inline-block;">← Back to Small-Group Classes</a>
    <span class="eyebrow" style="margin-top:18px;display:block;"><?= e($batch['curriculum_name'] ?? '') ?> · <?= e($batch['subject_name'] ?? '') ?></span>
    <h1 style="font-size:30px;"><?= e($batch['name']) ?>
      <span class="mode-badge mode-<?= e($modeCode) ?>"><?= e($batch['mode_name'] ?? ucfirst($modeCode)) ?></span>
    </h1>

    <div class="grid-2" style="margin-top:20px;align-items:start;">
      <div class="card">
        <span class="tag">Batch details</span>
        <ul style="margin-top:12px;">
          <li style="padding:8px 0;border-bottom:1px solid var(--line);"><b>Starts:</b> <?= e($batch['start_date'] ? date('j M Y', strtotime($batch['start_date'])) : 'TBC') ?></li>
          <li style="padding:8px 0;border-bottom:1px solid var(--line);"><b>Schedule:</b> <?= e($batch['schedule_text'] ?? 'TBC') ?></li>
          <li style="padding:8px 0;border-bottom:1px solid var(--line);"><b>Duration:</b> <?= e($batch['duration_text'] ?? 'TBC') ?></li>
          <li style="padding:8px 0;border-bottom:1px solid var(--line);"><b>Tutor:</b> <?= e($batch['tutor_name'] ?? 'To be assigned') ?></li>
          <li style="padding:8px 0;border-bottom:1px solid var(--line);"><b>Mode:</b> <?= e($batch['mode_name'] ?? ucfirst($modeCode)) ?><?= !empty($batch['mode_detail']) ? ' — ' . e($batch['mode_detail']) : '' ?></li>
          <li style="padding:8px 0;border-bottom:1px solid var(--line);"><b>Seats left:</b> <?= $avail ?> / <?= $max ?></li>
          <?php if (!empty($batch['price'])): ?>
            <li style="padding:8px 0;"><b>Fee:</b> <?= e(money($batch['price'], $batch['currency'] ?? 'INR')) ?></li>
          <?php endif; ?>
        </ul>
      </div>

      <div class="card">
        <span class="tag">Enrol in this batch</span>
        <?php if ($avail <= 0): ?>
          <p style="margin-top:12px;">This batch is currently <strong>full</strong>. <a href="<?= e(base_url('book')) ?>" class="btn-ghost">Book a consultation →</a> for the next cohort.</p>
        <?php else: ?>
          <form method="post" action="<?= e(base_url('enrol')) ?>" style="margin-top:12px;">
            <?= Csrf::field() ?>
            <input type="hidden" name="batch_id" value="<?= (int)$batch['id'] ?>">
            <div class="field" style="margin-bottom:14px;">
              <label>Full name</label>
              <input type="text" name="name" value="<?= e($old['name'] ?? '') ?>" class="<?= isset($errors['name']) ? 'invalid' : '' ?>" required>
              <?php if (isset($errors['name'])): ?><span class="err"><?= e($errors['name']) ?></span><?php endif; ?>
            </div>
            <div class="field" style="margin-bottom:14px;">
              <label>Email</label>
              <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" class="<?= isset($errors['email']) ? 'invalid' : '' ?>" required>
              <?php if (isset($errors['email'])): ?><span class="err"><?= e($errors['email']) ?></span><?php endif; ?>
            </div>
            <div class="field-row">
              <div class="field"><label>Phone (optional)</label><input type="text" name="phone" value="<?= e($old['phone'] ?? '') ?>"></div>
              <div class="field"><label>Country</label><input type="text" name="country" value="<?= e($old['country'] ?? '') ?>" placeholder="e.g. UAE"></div>
            </div>
            <button type="submit" class="btn btn-primary btn-block" style="margin-top:8px;">Request Enrolment</button>
            <p class="notice-inline" style="margin-top:12px;">We'll confirm your seat and share payment details. The <?= $modeCode === 'online' ? 'Zoom link' : 'venue details' ?> is shared with the class once your enrolment is confirmed.</p>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
