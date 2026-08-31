<section class="page-hero">
  <div class="wrap">
    <div class="crumbs"><a href="<?= e(base_url()) ?>">Home</a><span>›</span> Classes <span>›</span> 1-to-1</div>
    <span class="pill-eyebrow"><?= e(block('pages.oneToOne.eyebrow')) ?></span>
    <h1><?= e(block('pages.oneToOne.title')) ?></h1>
    <p><?= e(block('pages.oneToOne.intro')) ?></p>
  </div>
</section>
<section>
  <div class="wrap">
    <div class="grid-2">
      <div class="card">
        <span class="tag">What's included</span>
        <ul>
          <li style="padding:8px 0;border-bottom:1px solid var(--line);">Tutor matched to your exact curriculum &amp; level</li>
          <li style="padding:8px 0;border-bottom:1px solid var(--line);">Flexible scheduling across IST / GST / GMT / CET</li>
          <li style="padding:8px 0;border-bottom:1px solid var(--line);">Live 1-to-1 sessions on Zoom (or in-person)</li>
          <li style="padding:8px 0;">Homework &amp; past-paper practice between sessions</li>
        </ul>
      </div>
      <div class="card">
        <span class="tag">Booking flow</span>
        <ol style="counter-reset:m;">
          <li style="padding:10px 0;border-bottom:1px solid var(--line);display:flex;gap:10px;"><b class="mono">1.</b> Select curriculum → subject → 1-to-1</li>
          <li style="padding:10px 0;border-bottom:1px solid var(--line);display:flex;gap:10px;"><b class="mono">2.</b> Submit requirement / book consultation</li>
          <li style="padding:10px 0;display:flex;gap:10px;"><b class="mono">3.</b> Confirmation, then payment where applicable</li>
        </ol>
      </div>
    </div>

    <p class="notice-inline" style="margin-top:18px;max-width:640px;">This is an enquiry/request-based flow — our team confirms your exact time slot after you submit a request. A real-time availability calendar can be added as a future enhancement.</p>

    <div style="text-align:center;margin-top:44px;">
      <a href="<?= e(base_url('book?class_type=one_to_one')) ?>" class="btn btn-primary">Book a Free Consultation</a>
    </div>
  </div>
</section>
