<?php
require_once __DIR__ . '/admin-auth.php';
$title = 'Orders';

$pdo = db();
$status = $_GET['status'] ?? '';

$sql = "SELECT * FROM cosmetics_orders";
$params = [];
if ($status) {
    $sql .= " WHERE status = ?";
    $params[] = $status;
}
$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();

include __DIR__ . '/admin-layout.php';
?>

<div style="margin-bottom:16px;display:flex;gap:8px;flex-wrap:wrap;">
  <?php foreach (['' => 'All', 'pending' => 'Pending', 'paid' => 'Paid', 'shipped' => 'Shipped', 'failed' => 'Failed'] as $k => $l): ?>
    <a href="admin-orders.php<?= $k ? '?status=' . $k : '' ?>"
       class="admin-btn <?= $status === $k ? 'admin-btn--primary' : 'admin-btn--ghost' ?> admin-btn--sm"><?= $l ?></a>
  <?php endforeach; ?>
</div>

<table class="admin-table">
  <thead>
    <tr>
      <th>Reference</th>
      <th>Customer</th>
      <th>County</th>
      <th>Payment</th>
      <th>Total</th>
      <th>Status</th>
      <th>Date</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php if (empty($orders)): ?>
      <tr><td colspan="8" style="text-align:center;padding:30px;color:#6b6b6b;">No orders found.</td></tr>
    <?php else: foreach ($orders as $o): ?>
      <tr>
        <td><strong><?= e($o['reference']) ?></strong></td>
        <td><?= e($o['customer_name']) ?><br><small style="color:#6b6b6b;"><?= e($o['phone']) ?></small></td>
        <td><?= e($o['county']) ?></td>
        <td style="text-transform:uppercase;font-size:12px;"><?= e($o['payment_method']) ?></td>
        <td>KSh <?= number_format($o['total']) ?></td>
        <td><span class="badge badge--<?= e($o['status']) ?>"><?= e($o['status']) ?></span></td>
        <td><?= date('d M H:i', strtotime($o['created_at'])) ?></td>
        <td><a href="admin-order-view.php?id=<?= (int)$o['id'] ?>" class="admin-btn admin-btn--ghost admin-btn--sm">View</a></td>
      </tr>
    <?php endforeach; endif; ?>
  </tbody>
</table>

<?php include __DIR__ . '/admin-footer.php'; ?>