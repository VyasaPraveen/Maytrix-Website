<?php /** @var array|null $enrol @var array|null $batch */ ?>
<section>
  <div class="wrap" style="max-width:640px;">
    <div class="wizard">
      <div class="confirm-box">
        <div class="check">✓</div>
        <h3 style="font-size:22px;">Enrolment request received</h3>
        <?php if ($batch): ?>
          <p style="max-width:440px;margin:0 auto 10px;">You've requested a seat in <strong><?= e($batch['name']) ?></strong>.</p>
        <?php endif; ?>
        <p class="muted" style="max-width:460px;margin:0 auto;">Our team will confirm your seat and share payment details by email. Once your enrolment is confirmed, the
          <?= ($batch['mode_code'] ?? 'online') === 'online' ? 'class Zoom link' : 'venue details' ?>
          is shared with you automatically ahead of the first session.</p>
        <div style="margin-top:24px;">
          <a href="<?= e(base_url('small-group')) ?>" class="btn btn-outline btn-sm">Back to classes</a>
          <a href="<?= e(base_url()) ?>" class="btn btn-primary btn-sm">Return home</a>
        </div>
      </div>
    </div>
  </div>
</section>
