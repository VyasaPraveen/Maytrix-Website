<?php
/** @var \App\Core\Paginator $pager */
if (empty($pager) || $pager->total === 0) {
    return;
}
$win  = $pager->window();
$first = $win[0];
$last  = end($win);
?>
<div class="pagination">
  <div class="pagination-info">
    Showing <strong><?= $pager->from ?></strong>–<strong><?= $pager->to ?></strong> of <strong><?= $pager->total ?></strong>
  </div>
  <?php if ($pager->pages > 1): ?>
  <nav class="pagination-nav" aria-label="Pagination">
    <?php if ($pager->hasPrev()): ?>
      <a class="page-link" href="<?= e($pager->url($pager->page - 1)) ?>" rel="prev">‹ Prev</a>
    <?php else: ?>
      <span class="page-link disabled">‹ Prev</span>
    <?php endif; ?>

    <?php if ($first > 1): ?>
      <a class="page-link" href="<?= e($pager->url(1)) ?>">1</a>
      <?php if ($first > 2): ?><span class="page-gap">…</span><?php endif; ?>
    <?php endif; ?>

    <?php foreach ($win as $p): ?>
      <?php if ($p === $pager->page): ?>
        <span class="page-link active" aria-current="page"><?= $p ?></span>
      <?php else: ?>
        <a class="page-link" href="<?= e($pager->url($p)) ?>"><?= $p ?></a>
      <?php endif; ?>
    <?php endforeach; ?>

    <?php if ($last < $pager->pages): ?>
      <?php if ($last < $pager->pages - 1): ?><span class="page-gap">…</span><?php endif; ?>
      <a class="page-link" href="<?= e($pager->url($pager->pages)) ?>"><?= $pager->pages ?></a>
    <?php endif; ?>

    <?php if ($pager->hasNext()): ?>
      <a class="page-link" href="<?= e($pager->url($pager->page + 1)) ?>" rel="next">Next ›</a>
    <?php else: ?>
      <span class="page-link disabled">Next ›</span>
    <?php endif; ?>
  </nav>
  <?php endif; ?>
</div>
