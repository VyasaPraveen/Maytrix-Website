<?php /** @var array $curricula @var array $subjects @var array $modes @var array $prefill @var array $errors @var array $old */
use App\Core\Csrf;
$preCurric = $prefill['curriculum_id'] ?? ($old['curriculum_id'] ?? '');
$preSubject = $prefill['subject_id'] ?? ($old['subject_id'] ?? '');
$preType = $prefill['class_type'] ?? ($old['class_type'] ?? '');
?>
<section class="page-hero" style="text-align:center;">
  <div class="wrap">
    <span class="pill-eyebrow"><?= e(block('pages.book.eyebrow')) ?></span>
    <h1><?= e(block('pages.book.title')) ?></h1>
    <p style="max-width:520px;margin-left:auto;margin-right:auto;"><?= e(block('pages.book.intro')) ?></p>
  </div>
</section>
<section>
  <div class="wrap">
    <form method="post" action="<?= e(base_url('book')) ?>" class="wizard">
      <?= Csrf::field() ?>
      <div class="wizard-progress">
        <i class="done" data-p="1"></i><i data-p="2"></i><i data-p="3"></i>
      </div>

      <!-- hidden values set by choice buttons -->
      <input type="hidden" id="wiz_curriculum_id" name="curriculum_id" value="<?= e($preCurric) ?>">
      <input type="hidden" id="wiz_subject_id" name="subject_id" value="<?= e($preSubject) ?>">
      <input type="hidden" id="wiz_class_type" name="class_type" value="<?= e($preType) ?>">
      <input type="hidden" id="wiz_mode_id" name="mode_id" value="<?= e($old['mode_id'] ?? '') ?>">

      <!-- step 1: curriculum -->
      <div class="wizard-step active" data-step="1">
        <h3 style="font-size:18px;">1. Choose your curriculum</h3>
        <div class="choice-grid two" data-choice-group="curriculum_id">
          <?php foreach ($curricula as $c): ?>
            <button type="button" class="choice <?= (string)$preCurric === (string)$c['id'] ? 'selected' : '' ?>" data-value="<?= (int)$c['id'] ?>">
              <strong><?= e($c['name']) ?></strong><span><?= e($c['short_name'] ?? '') ?></span>
            </button>
          <?php endforeach; ?>
        </div>
        <?php if (isset($errors['curriculum_id'])): ?><span class="err"><?= e($errors['curriculum_id']) ?></span><?php endif; ?>
      </div>

      <!-- step 2: subject + type + mode -->
      <div class="wizard-step" data-step="2">
        <h3 style="font-size:18px;">2. Subject, class type &amp; mode</h3>
        <p style="font-size:13px;text-transform:uppercase;letter-spacing:.06em;color:var(--ink-soft);margin-bottom:10px;">Subject</p>
        <div class="choice-grid two" data-choice-group="subject_id">
          <?php foreach ($subjects as $s): ?>
            <button type="button" class="choice <?= (string)$preSubject === (string)$s['id'] ? 'selected' : '' ?>" data-value="<?= (int)$s['id'] ?>">
              <strong><?= e($s['name']) ?></strong><span><?= e($s['description'] ? mb_substr($s['description'],0,32) : '') ?></span>
            </button>
          <?php endforeach; ?>
        </div>
        <p style="font-size:13px;text-transform:uppercase;letter-spacing:.06em;color:var(--ink-soft);margin:20px 0 10px;">Class type</p>
        <div class="choice-grid two" data-choice-group="class_type">
          <button type="button" class="choice <?= $preType === 'one_to_one' ? 'selected' : '' ?>" data-value="one_to_one"><strong>1-to-1</strong><span>Personal pace</span></button>
          <button type="button" class="choice <?= $preType === 'small_group' ? 'selected' : '' ?>" data-value="small_group"><strong>Small-Group</strong><span>Up to 6 students</span></button>
        </div>
        <p style="font-size:13px;text-transform:uppercase;letter-spacing:.06em;color:var(--ink-soft);margin:20px 0 10px;">Preferred mode</p>
        <div class="choice-grid" data-choice-group="mode_id">
          <?php foreach ($modes as $m): ?>
            <button type="button" class="choice" data-value="<?= (int)$m['id'] ?>"><strong><?= e($m['name']) ?></strong></button>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- step 3: details -->
      <div class="wizard-step" data-step="3">
        <h3 style="font-size:18px;">3. Your details</h3>
        <div class="field-row">
          <div class="field">
            <label>Full name</label>
            <input type="text" name="name" value="<?= e($old['name'] ?? '') ?>" class="<?= isset($errors['name']) ? 'invalid' : '' ?>">
            <?php if (isset($errors['name'])): ?><span class="err"><?= e($errors['name']) ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label>Email</label>
            <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" class="<?= isset($errors['email']) ? 'invalid' : '' ?>">
            <?php if (isset($errors['email'])): ?><span class="err"><?= e($errors['email']) ?></span><?php endif; ?>
          </div>
        </div>
        <div class="field-row">
          <div class="field"><label>Phone / WhatsApp</label><input type="text" name="phone" value="<?= e($old['phone'] ?? '') ?>" placeholder="e.g. +971 50 000 0000"></div>
          <div class="field"><label>Country</label><input type="text" name="country" value="<?= e($old['country'] ?? '') ?>" placeholder="e.g. UAE"></div>
        </div>
        <div class="field-row">
          <div class="field"><label>Preferred contact</label>
            <select name="preferred_contact"><option>Email</option><option>WhatsApp</option><option>Phone call</option></select>
          </div>
          <div class="field"><label>Time zone</label><input type="text" name="timezone" value="<?= e($old['timezone'] ?? '') ?>" placeholder="e.g. GST"></div>
        </div>
        <div class="field" style="margin-bottom:8px;">
          <label>Anything we should know? (optional)</label>
          <textarea name="message" placeholder="Target grade, exam date, specific topics..."><?= e($old['message'] ?? '') ?></textarea>
        </div>
      </div>

      <div class="wizard-nav">
        <button type="button" class="btn btn-outline btn-sm" id="wizBack" style="visibility:hidden;">Back</button>
        <button type="button" class="btn btn-primary btn-sm" id="wizNext">Continue</button>
      </div>
    </form>
  </div>
</section>
