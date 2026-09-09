<?php /** @var array $curricula @var array $subjects @var array $modes @var array $pages @var array $prefill @var array $errors @var array $old */
use App\Core\Csrf;
$preSubject = $prefill['subject_id'] ?? ($old['subject_id'] ?? '');
$preCurric  = $prefill['curriculum_id'] ?? ($old['curriculum_id'] ?? '');
$preLevel   = $old['level'] ?? '';

// Levels grouped by subject → curriculum (from the published curriculum pages).
$curNames = [];
foreach ($curricula as $c) { $curNames[(int) $c['id']] = $c['short_name'] ?: $c['name']; }
$levelSets = [];
foreach ($pages as $p) {
    $levels = is_array($p['badges'] ?? null) ? $p['badges'] : [];
    $levelSets[(int) $p['subject_id']][] = [
        'curriculum_id' => (int) $p['curriculum_id'],
        'name'          => $curNames[(int) $p['curriculum_id']] ?? '',
        'levels'        => $levels,
    ];
}
?>
<section class="page-hero page-hero--plain" style="text-align:center;">
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
      <input type="hidden" id="wiz_subject_id" name="subject_id" value="<?= e($preSubject) ?>">
      <input type="hidden" id="wiz_curriculum_id" name="curriculum_id" value="<?= e($preCurric) ?>">
      <input type="hidden" id="wiz_level" name="level" value="<?= e($preLevel) ?>">
      <input type="hidden" id="wiz_class_type" name="class_type" value="<?= e($old['class_type'] ?? 'one_to_one') ?>">

      <!-- step 1: subject -->
      <div class="wizard-step active" data-step="1">
        <h3 style="font-size:18px;">1. Choose your subject</h3>
        <div class="choice-grid two" data-choice-group="subject_id">
          <?php foreach ($subjects as $s): ?>
            <button type="button" class="choice <?= (string) $preSubject === (string) $s['id'] ? 'selected' : '' ?>" data-value="<?= (int) $s['id'] ?>">
              <strong><?= e($s['name']) ?></strong>
            </button>
          <?php endforeach; ?>
        </div>
        <?php if (isset($errors['subject_id'])): ?><span class="err"><?= e($errors['subject_id']) ?></span><?php endif; ?>
      </div>

      <!-- step 2: level (depends on subject) -->
      <div class="wizard-step" data-step="2">
        <h3 style="font-size:18px;">2. Choose your level</h3>
        <?php foreach ($subjects as $s): ?>
          <div class="level-set" data-subject="<?= (int) $s['id'] ?>" hidden>
            <?php foreach (($levelSets[(int) $s['id']] ?? []) as $grp): ?>
              <p class="wiz-sublabel"><?= e($grp['name']) ?></p>
              <div class="choice-grid two">
                <?php foreach ($grp['levels'] as $lv): ?>
                  <button type="button" class="choice level-btn <?= ((string) $preCurric === (string) $grp['curriculum_id'] && $preLevel === $lv) ? 'selected' : '' ?>"
                          data-curriculum="<?= $grp['curriculum_id'] ?>" data-level="<?= e($lv) ?>">
                    <strong><?= e($lv) ?></strong>
                  </button>
                <?php endforeach; ?>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>
        <p class="wiz-hint">Pick the level or paper you're preparing for. Not sure? Choose the closest — we'll confirm it in your free consultation.</p>
        <?php if (isset($errors['curriculum_id'])): ?><span class="err"><?= e($errors['curriculum_id']) ?></span><?php endif; ?>
      </div>

      <!-- step 3: contact details -->
      <div class="wizard-step" data-step="3">
        <h3 style="font-size:18px;">3. Your details</h3>
        <div class="field-row">
          <div class="field">
            <label for="bk-name">Full name</label>
            <input id="bk-name" type="text" name="name" value="<?= e($old['name'] ?? '') ?>" required class="<?= isset($errors['name']) ? 'invalid' : '' ?>">
            <?php if (isset($errors['name'])): ?><span class="err"><?= e($errors['name']) ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label for="bk-email">Email</label>
            <input id="bk-email" type="email" name="email" value="<?= e($old['email'] ?? '') ?>" required class="<?= isset($errors['email']) ? 'invalid' : '' ?>">
            <?php if (isset($errors['email'])): ?><span class="err"><?= e($errors['email']) ?></span><?php endif; ?>
          </div>
        </div>
        <div class="field-row">
          <div class="field"><label for="bk-phone">Phone / WhatsApp</label><input id="bk-phone" type="text" name="phone" value="<?= e($old['phone'] ?? '') ?>" placeholder="e.g. +971 50 000 0000"></div>
          <div class="field"><label for="bk-country">Country</label><input id="bk-country" type="text" name="country" value="<?= e($old['country'] ?? '') ?>" placeholder="e.g. UAE"></div>
        </div>
        <div class="field-row">
          <div class="field"><label for="bk-contact">Preferred contact</label>
            <select id="bk-contact" name="preferred_contact"><option>Email</option><option>WhatsApp</option><option>Phone call</option></select>
          </div>
          <div class="field"><label for="bk-tz">Time zone</label><input id="bk-tz" type="text" name="timezone" value="<?= e($old['timezone'] ?? '') ?>" placeholder="e.g. GST"></div>
        </div>
        <div class="field" style="margin-bottom:8px;">
          <label for="bk-msg">Anything we should know? (optional)</label>
          <textarea id="bk-msg" name="message" placeholder="Target grade, exam date, specific topics..."><?= e($old['message'] ?? '') ?></textarea>
        </div>
      </div>

      <p class="wiz-guard" id="wizGuard" role="alert" aria-live="assertive" hidden></p>
      <div class="wizard-nav">
        <button type="button" class="btn btn-outline btn-sm" id="wizBack" style="visibility:hidden;">Back</button>
        <button type="button" class="btn btn-primary btn-sm" id="wizNext">Continue</button>
      </div>
    </form>
  </div>
</section>
