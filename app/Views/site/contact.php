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
        <div class="card">
          <span class="tag">Reach us directly</span>
          <?php
            $cEmail = $settings['contact_email'] ?? '';
            $cWa    = $settings['contact_whatsapp'] ?? '';
            $waDigits = preg_replace('/\D+/', '', (string) $cWa);
          ?>
          <ul class="contact-methods">
            <?php if ($cEmail !== ''): ?>
            <li>
              <span class="cm-ico" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
              </span>
              <span class="cm-body"><span class="cm-label">Email</span><a href="mailto:<?= e($cEmail) ?>"><?= e($cEmail) ?></a></span>
            </li>
            <?php endif; ?>
            <?php if ($cWa !== ''): ?>
            <li>
              <span class="cm-ico wa" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.46 1.32 4.97L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.91-4.45 9.91-9.91C21.96 6.45 17.5 2 12.04 2Zm0 18.15h-.01a8.2 8.2 0 0 1-4.18-1.15l-.3-.18-3.11.82.83-3.04-.2-.31a8.23 8.23 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.25-8.24 2.2 0 4.27.86 5.83 2.42a8.19 8.19 0 0 1 2.41 5.83c0 4.55-3.7 8.24-8.24 8.24Zm4.52-6.17c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.13-.16.25-.64.81-.79.98-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.02-.39.11-.51.11-.11.25-.29.37-.43.13-.14.17-.25.25-.41.08-.16.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.4-.42-.56-.43l-.48-.01c-.16 0-.43.06-.66.31-.23.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.16 1.75 2.67 4.24 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.11-.22-.17-.47-.29Z"/></svg>
              </span>
              <span class="cm-body"><span class="cm-label">WhatsApp</span><a href="https://wa.me/<?= e($waDigits) ?>" target="_blank" rel="noopener"><?= e($cWa) ?></a></span>
            </li>
            <?php endif; ?>
          </ul>
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
              <option>IBDP</option><option>IBMYP</option><option>IGCSE</option><option>AS &amp; A Level</option><option>Not sure yet</option>
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
