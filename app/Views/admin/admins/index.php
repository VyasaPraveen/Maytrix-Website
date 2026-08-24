<?php /** @var array $rows */
use App\Core\Csrf;
use App\Core\Auth;
$meId = Auth::id();
?>
<div class="panel">
  <div class="panel-head">
    <h3>Admin Users <span class="muted">(<?= (int) ($pager->total ?? count($rows)) ?>)</span></h3>
    <a href="<?= e(admin_url('admins/create')) ?>" class="btn btn-primary btn-sm">+ New Admin</a>
  </div>
  <div class="table-wrap">
    <table class="data">
      <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Active</th><th>Last login</th><th></th></tr></thead>
      <tbody>
        <?php foreach ($rows as $a): ?>
          <tr>
            <td><strong><?= e($a['name']) ?></strong><?= (int)$a['id'] === $meId ? ' <span class="tag tag-green">You</span>' : '' ?></td>
            <td><?= e($a['email']) ?></td>
            <td><span class="tag tag-grey"><?= e($a['role']) ?></span></td>
            <td><?= (int)$a['is_active'] ? '<span class="tag tag-green">Yes</span>' : '<span class="tag tag-red">No</span>' ?></td>
            <td class="muted"><?= e($a['last_login_at'] ? date('j M, H:i', strtotime($a['last_login_at'])) : 'Never') ?></td>
            <td class="actions">
              <a href="<?= e(admin_url('admins/' . $a['id'] . '/edit')) ?>" class="btn btn-outline btn-sm">Edit</a>
              <?php if ((int)$a['id'] !== $meId): ?>
                <form method="post" action="<?= e(admin_url('admins/' . $a['id'] . '/delete')) ?>" style="display:inline;" data-confirm="Delete this admin user?">
                  <?= Csrf::field() ?><button class="btn btn-danger btn-sm">Delete</button>
                </form>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php include __DIR__ . '/../partials/pagination.php'; ?>
</div>
