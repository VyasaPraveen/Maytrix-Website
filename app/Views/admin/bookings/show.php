<?php /** @var array $booking */
use App\Core\Csrf;
$statuses = ['new' => 'New', 'contacted' => 'Contacted', 'confirmed' => 'Confirmed', 'closed' => 'Closed'];
$linked = !empty($booking['student_id']);
// The student profile is the source of truth for contact details; the booking's
// own snapshot is used only as a fallback when no student is linked.
$profile = function (string $field) use ($booking, $linked) {
    $studentVal = $booking['student_' . $field] ?? '';
    $bookingVal = $booking[$field] ?? '';
    $value = ($linked && $studentVal !== '') ? $studentVal : $bookingVal;
    $fromProfile = $linked && $studentVal !== '' && $studentVal !== $bookingVal;
    return [$value, $fromProfile];
};
?>
<div style="margin-bottom:16px;"><a href="<?= e(admin_url('bookings')) ?>" class="btn btn-outline btn-sm">← Back to bookings</a></div>

<div class="form-grid">
  <div class="panel">
    <div class="panel-head"><h3>Request details</h3></div>
    <div class="panel-body">
      <table class="data">
        <tr><th>Name</th><td><?= e($booking['name']) ?></td></tr>
        <tr><th>Email</th><td><?= e($booking['email']) ?></td></tr>
        <?php [$phone, $phoneFromProfile] = $profile('phone'); ?>
        <tr><th>Phone</th><td><?= e($phone ?: '—') ?><?= $phoneFromProfile || (empty($booking['phone']) && !empty($booking['student_phone'])) ? ' <span class="muted">(from student profile)</span>' : '' ?></td></tr>
        <?php [$country, $countryFromProfile] = $profile('country'); ?>
        <tr><th>Country</th><td><?= e($country ?: '—') ?><?= $countryFromProfile ? ' <span class="muted">(from student profile)</span>' : '' ?></td></tr>
        <tr><th>Curriculum</th><td><?= e($booking['curriculum_name'] ?? '—') ?></td></tr>
        <tr><th>Subject</th><td><?= e($booking['subject_name'] ?? '—') ?></td></tr>
        <tr><th>Class type</th><td><?= e($booking['class_type'] === 'one_to_one' ? '1-to-1' : 'Small-Group') ?></td></tr>
        <tr><th>Mode</th><td><?= e($booking['mode_name'] ?? '—') ?></td></tr>
        <tr><th>Preferred contact</th><td><?= e($booking['preferred_contact'] ?: '—') ?></td></tr>
        <tr><th>Message</th><td><?= nl2br(e($booking['message'] ?: '—')) ?></td></tr>
        <tr><th>Received</th><td><?= e($booking['created_at']) ?></td></tr>
      </table>
    </div>
  </div>

  <div class="panel">
    <div class="panel-head"><h3>Manage &amp; share Zoom link</h3></div>
    <div class="panel-body">
      <form method="post" action="<?= e(admin_url('bookings/' . $booking['id'])) ?>">
        <?= Csrf::field() ?>
        <div class="form-field full">
          <label>Status</label>
          <select name="status">
            <?php foreach ($statuses as $val => $label): ?>
              <option value="<?= e($val) ?>" <?= $booking['status'] === $val ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-field full">
          <label>Scheduled date/time</label>
          <input type="datetime-local" name="scheduled_at" value="<?= e($booking['scheduled_at'] ? date('Y-m-d\TH:i', strtotime($booking['scheduled_at'])) : '') ?>">
        </div>
        <div class="form-field full">
          <label>Zoom / class link</label>
          <input type="text" name="zoom_link" value="<?= e($booking['zoom_link'] ?? '') ?>" placeholder="https://zoom.us/j/...">
          <span class="hint">Shared with the student when you confirm and tick “email the student”.</span>
        </div>
        <div class="form-field full">
          <label>Internal notes</label>
          <textarea name="admin_notes" rows="3"><?= e($booking['admin_notes'] ?? '') ?></textarea>
        </div>
        <div class="check-row" style="margin-top:8px;">
          <input type="checkbox" name="notify" value="1" id="notify">
          <label for="notify" style="font-weight:500;">Email the student their confirmation + link</label>
        </div>
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
      <form method="post" action="<?= e(admin_url('bookings/' . $booking['id'] . '/delete')) ?>" data-confirm="Delete this booking?" style="margin-top:12px;">
        <?= Csrf::field() ?><button class="btn btn-danger btn-sm">Delete booking</button>
      </form>
    </div>
  </div>
</div>
