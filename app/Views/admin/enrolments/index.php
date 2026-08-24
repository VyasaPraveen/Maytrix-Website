<?php /** @var array $rows @var string|null $filter */
use App\Core\Csrf;
$statusTag = ['pending' => 'tag-amber', 'confirmed' => 'tag-green', 'cancelled' => 'tag-red', 'waitlist' => 'tag-grey'];
$filters = ['' => 'All', 'pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled'];
?>
<div class="panel">
  <div class="panel-head">
    <h3>Enrolments <span class="muted">(<?= (int) ($pager->total ?? count($rows)) ?>)</span></h3>
    <div>
      <?php foreach ($filters as $val => $label): ?>
        <a href="<?= e(admin_url('enrolments' . ($val ? '?status=' . $val : ''))) ?>"
           class="btn btn-sm <?= (string)$filter === (string)$val ? 'btn-teal' : 'btn-outline' ?>"><?= e($label) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Student</th><th>Batch</th><th>Status</th><th>Payment</th><th>Actions</th></tr></thead>
      <tbody>
        <?php if (empty($rows)): ?>
          <tr><td colspan="5" class="empty">No enrolments found.</td></tr>
        <?php else: foreach ($rows as $en): ?>
          <tr>
            <td><strong><?= e($en['student_name']) ?></strong><br><span class="muted"><?= e($en['student_email']) ?></span></td>
            <td><?= e($en['batch_name']) ?></td>
            <td><span class="tag <?= $statusTag[$en['status']] ?? 'tag-grey' ?>"><?= e($en['status']) ?></span></td>
            <td><span class="tag <?= $en['payment_status'] === 'paid' ? 'tag-green' : 'tag-grey' ?>"><?= e($en['payment_status']) ?></span></td>
            <td class="actions">
              <?php if ($en['status'] !== 'confirmed'): ?>
                <form method="post" action="<?= e(admin_url('enrolments/' . $en['id'] . '/confirm')) ?>" style="display:inline;" data-confirm="Confirm and share the class link with the student?">
                  <?= Csrf::field() ?><button class="btn btn-teal btn-sm">Confirm</button>
                </form>
              <?php endif; ?>
              <?php if ($en['status'] !== 'cancelled'): ?>
                <form method="post" action="<?= e(admin_url('enrolments/' . $en['id'] . '/cancel')) ?>" style="display:inline;" data-confirm="Cancel this enrolment?">
                  <?= Csrf::field() ?><button class="btn btn-outline btn-sm">Cancel</button>
                </form>
              <?php endif; ?>
              <form method="post" action="<?= e(admin_url('enrolments/' . $en['id'] . '/delete')) ?>" style="display:inline;" data-confirm="Delete this enrolment permanently?">
                <?= Csrf::field() ?><button class="btn btn-danger btn-sm">Delete</button>
              </form>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
  <?php include __DIR__ . '/../partials/pagination.php'; ?>
</div>
