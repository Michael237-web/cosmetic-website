<?php
require_once __DIR__ . '/admin-auth.php';
$title = 'Dashboard';

$pdo = db();
$totalProducts = (int)$pdo->query("SELECT COUNT(*) FROM cosmetics_products")->fetchColumn();
$totalOrders   = (int)$pdo->query("SELECT COUNT(*) FROM cosmetics_orders")->fetchColumn();
$pendingOrders = (int)$pdo->query("SELECT COUNT(*) FROM cosmetics_orders WHERE status = 'pending'")->fetchColumn();
$totalRevenue  = (int)$pdo->query("SELECT COALESCE(SUM(total),0) FROM cosmetics_orders WHERE status = 'paid'")->fetchColumn();
$unreadMsgs    = (int)$pdo->query("SELECT COUNT(*) FROM cosmetics_contacts WHERE is_read = 0")->fetchColumn();

$recent = $pdo->query("SELECT * FROM cosmetics_orders ORDER BY created_at DESC LIMIT 6")->fetchAll();

include __DIR__ . '/admin-layout.php';
?>

<div class="stat-grid">
  <div class="stat-card stat-card--pink">
    <div class="stat-card__label">Total Revenue (paid)</div>
    <div class="stat-card__value">KSh <?= number_format($totalRevenue) ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-card__label">Total Orders</div>
    <div class="stat-card__value"><?= $totalOrders ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-card__label">Pending</div>
    <div class="stat-card__value"><?= $pendingOrders ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-card__label">Products</div>
    <div class="stat-card__value"><?= $totalProducts ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-card__label">Unread Messages</div>
    <div class="stat-card__value"><?= $unreadMsgs ?></div>
  </div>
</div>

<h3 style="margin-bottom:14px;">Recent Orders</h3>
<table class="admin-table">
  <thead>
    <tr>
      <th>Reference</th>
      <th>Customer</th>
      <th>County</th>
      <th>Total</th>
      <th>Status</th>
      <th>Date</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($recent)): ?>
      <tr><td colspan="7" style="text-align:center;color:#6b6b6b;padding:30px;">No orders yet.</td></tr>
    <?php else: foreach ($recent as $o): ?>
      <tr>
        <td><strong><?= e($o['reference']) ?></strong></td>
        <td><?= e($o['customer_name']) ?><br><small style="color:#6b6b6b;"><?= e($o['phone']) ?></small></td>
        <td><?= e($o['county']) ?></td>
        <td>KSh <?= number_format($o['total']) ?></td>
        <td><span class="badge badge--<?= e($o['status']) ?>"><?= e($o['status']) ?></span></td>
        <td><?= date('d M Y H:i', strtotime($o['created_at'])) ?></td>
        <td><a href="admin-order-view.php?id=<?= (int)$o['id'] ?>" class="admin-btn admin-btn--ghost admin-btn--sm">View</a></td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<?php include __DIR__ . '/admin-footer.php'; ?>