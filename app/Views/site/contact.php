<?php /** @var array $settings @var array $errors @var array $old */
use App\Core\Csrf;
?>
<section>
  <div class="wrap">
    <span class="eyebrow">Contact</span>
    <h1 style="font-size:32px;">Talk to us</h1>
    <div class="grid-2" style="margin-top:30px;align-items:start;">
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
            <label>Full name</label>
            <input type="text" name="name" value="<?= e($old['name'] ?? '') ?>" class="<?= isset($errors['name']) ? 'invalid' : '' ?>" required>
            <?php if (isset($errors['name'])): ?><span class="err"><?= e($errors['name']) ?></span><?php endif; ?>
          </div>
          <div class="field">
            <label>Email</label>
            <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>" class="<?= isset($errors['email']) ? 'invalid' : '' ?>" required>
            <?php if (isset($errors['email'])): ?><span class="err"><?= e($errors['email']) ?></span><?php endif; ?>
          </div>
        </div>
        <div class="field-row">
          <div class="field"><label>Country</label><input type="text" name="country" value="<?= e($old['country'] ?? '') ?>" placeholder="e.g. UAE, UK, India"></div>
          <div class="field"><label>Curriculum</label>
            <select name="curriculum">
              <option value="">Select…</option>
              <option>IB Diploma</option><option>IB MYP</option><option>IGCSE</option><option>AS &amp; A Level</option><option>Not sure yet</option>
            </select>
          </div>
        </div>
        <div class="field" style="margin-bottom:18px;">
          <label>Message</label>
          <textarea name="message" placeholder="Tell us a bit about what you're looking for..." class="<?= isset($errors['message']) ? 'invalid' : '' ?>"><?= e($old['message'] ?? '') ?></textarea>
          <?php if (isset($errors['message'])): ?><span class="err"><?= e($errors['message']) ?></span><?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Send Message</button>
      </form>
    </div>
  </div>
</section>
