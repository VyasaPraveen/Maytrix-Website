<?php
/** @var array $metrics @var array $revenue @var array $enrolByStatus
 *  @var array $bookingByStatus @var array $byCurriculum @var array $byCountry @var array $batchFill */

// Reusable CSS bar-chart renderer.
$bars = function (array $data, string $color = '') {
    $max = 0;
    foreach ($data as $d) { $max = max($max, (int) $d['cnt']); }
    if (empty($data)) { echo '<p class="muted">No data yet.</p>'; return; }
    echo '<div class="bars">';
    foreach ($data as $d) {
        $pct = $max > 0 ? round((int) $d['cnt'] / $max * 100) : 0;
        echo '<div class="bar-row">';
        echo '<span class="bar-label">' . e((string) $d['k']) . '</span>';
        echo '<span class="bar-track"><span class="bar-fill ' . e($color) . '" style="width:' . $pct . '%"></span></span>';
        echo '<span class="bar-val">' . number_format((int) $d['cnt']) . '</span>';
        echo '</div>';
    }
    echo '</div>';
};
$money = function ($amount, $currency) {
    $sym = ['INR' => '₹', 'USD' => '$', 'GBP' => '£', 'EUR' => '€', 'AED' => 'AED '];
    return ($sym[$currency] ?? ($currency . ' ')) . number_format((float) $amount, 2);
};
?>
<p class="page-lead">A live snapshot of demand, enrolments, payments and reach — aggregated from the content database.</p>

<div class="report-grid">
  <div class="metric"><div class="k">Students</div><div class="v teal"><?= number_format($metrics['students']) ?></div><div class="d">total records</div></div>
  <div class="metric"><div class="k">Confirmed Enrolments</div><div class="v"><?= number_format($metrics['confirmedEnrol']) ?></div><div class="d"><?= number_format($metrics['pendingEnrol']) ?> pending</div></div>
  <div class="metric"><div class="k">Paid Enrolments</div><div class="v brass"><?= number_format($metrics['paidCount']) ?></div><div class="d"><?= number_format($metrics['unpaidCount']) ?> unpaid / other</div></div>
  <div class="metric"><div class="k">Open Bookings</div><div class="v rust"><?= number_format($metrics['openBookings']) ?></div><div class="d">new + contacted</div></div>
</div>

<div class="panel">
  <div class="panel-head"><h3>Revenue (paid enrolments)</h3></div>
  <div class="panel-body">
    <?php if (empty($revenue)): ?>
      <p class="muted">No payments recorded yet. Revenue appears here once enrolments are marked <strong>paid</strong>.</p>
    <?php else: ?>
      <div class="report-grid" style="margin-bottom:0;">
        <?php foreach ($revenue as $r): ?>
          <div class="metric">
            <div class="k"><?= e($r['currency']) ?> revenue</div>
            <div class="v teal"><?= e($money($r['total'], $r['currency'])) ?></div>
            <div class="d"><?= number_format((int) $r['cnt']) ?> paid enrolment(s)</div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<div class="panel-grid">
  <div class="panel">
    <div class="panel-head"><h3>Enrolments by status</h3></div>
    <div class="panel-body"><?php $bars($enrolByStatus, ''); ?></div>
  </div>
  <div class="panel">
    <div class="panel-head"><h3>Bookings by status</h3></div>
    <div class="panel-body"><?php $bars($bookingByStatus, 'brass'); ?></div>
  </div>
</div>

<div class="panel-grid">
  <div class="panel">
    <div class="panel-head"><h3>Demand by curriculum</h3></div>
    <div class="panel-body"><?php $bars($byCurriculum, ''); ?></div>
  </div>
  <div class="panel">
    <div class="panel-head"><h3>Students by country</h3></div>
    <div class="panel-body"><?php $bars($byCountry, 'rust'); ?></div>
  </div>
</div>

<div class="panel">
  <div class="panel-head"><h3>Batch fill rates <span class="muted">(top 8 by demand)</span></h3></div>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Batch</th><th>Course</th><th>Seats filled</th><th>Fill</th><th>Status</th></tr></thead>
      <tbody>
        <?php if (empty($batchFill)): ?>
          <tr><td colspan="5" class="empty">No batches yet.</td></tr>
        <?php else: foreach ($batchFill as $b):
            $max = (int) $b['max_seats']; $avail = (int) $b['available_seats']; $taken = $max - $avail;
            $fill = $max > 0 ? round($taken / $max * 100) : 0;
            $barClass = $fill >= 90 ? 'high' : ($fill >= 50 ? 'mid' : '');
        ?>
          <tr>
            <td><strong><?= e($b['name']) ?></strong></td>
            <td class="muted"><?= e($b['course_title'] ?? '—') ?></td>
            <td><?= $taken ?> / <?= $max ?></td>
            <td style="min-width:140px;">
              <div class="seat-bar"><i class="<?= $barClass ?>" style="width:<?= $fill ?>%;"></i></div>
              <span class="muted"><?= $fill ?>%</span>
            </td>
            <td><span class="tag <?= $b['status'] === 'open' ? 'tag-green' : 'tag-grey' ?>"><?= e($b['status']) ?></span></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>
