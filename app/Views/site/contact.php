<?php /** @var array $settings @var array $errors @var array $old */
use App\Core\Csrf;
?>
<section class="page-hero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= e(base_url()) ?>">Home</a><span>›</span> Contact</div>
    <span class="pill-eyebrow"><?= e(block('pages.contact.eyebrow')) ?></span>
    <h1><?= e(block('pages.contact.title')) ?></h1>
    <p><?= e(block('pages.contact.intro')) ?></p>
  </div>
</section>
<section>
  <div class="wrap">
    <div class="grid-2" style="align-items:start;">
      <div>
        <p>Have a question before booking a consultation? Send us a note and we'll get back to you within one business day.</p>
        <div class="card" style="margin-top:20px;">
          <span class="tag">Reach us directly</span>
          <p style="margin-top:12px;font-size:14.5px;">
            Email: <?= e($settings['contact_email'] ?? '') ?><br>
            WhatsApp: <?= e($settings['contact_whatsapp'] ?? '') ?>
          </p>
        </div>
      </div>
      <form method="post" action="<?= e(base_url('contact')) ?>">
        <?= Csrf::field() ?>
        <div class="field-row">
          <div class="field">
            <label for="cf-name">Full name</label>
            <input id="cf-name" type="text" name="name" value="<?= e($old['name'] ?? '') ?>" class="<?= isset($errors['name']) ? 'invalid' : '' ?>" required>
            <?php if (isset($errors['name'])): ?><span class="err"><?= e($errors['name']) ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label for="cf-email">Email</label>
            <input id="cf-email" type="email" name="email" value="<?= e($old['email'] ?? '') ?>" class="<?= isset($errors['email']) ? 'invalid' : '' ?>" required>
            <?php if (isset($errors['email'])): ?><span class="err"><?= e($errors['email']) ?></span><?php endif; ?>
          </div>
        </div>
        <div class="field-row">
          <div class="field"><label for="cf-country">Country</label><input id="cf-country" type="text" name="country" value="<?= e($old['country'] ?? '') ?>" placeholder="e.g. UAE, UK, India"></div>
          <div class="field"><label for="cf-curriculum">Curriculum</label>
            <select id="cf-curriculum" name="curriculum">
              <option value="">Select…</option>
              <option>IB Diploma</option><option>IBMYP</option><option>IGCSE</option><option>AS &amp; A Level</option><option>Not sure yet</option>
            </select>
          </div>
        </div>
        <div class="field" style="margin-bottom:18px;">
          <label for="cf-message">Message</label>
          <textarea id="cf-message" name="message" placeholder="Tell us a bit about what you're looking for..." class="<?= isset($errors['message']) ? 'invalid' : '' ?>"><?= e($old['message'] ?? '') ?></textarea>
          <?php if (isset($errors['message'])): ?><span class="err"><?= e($errors['message']) ?></span><?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Send Message</button>
      </form>
    </div>
  </div>
</section>
