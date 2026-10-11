<?php
require_once __DIR__ . '/admin-auth.php';
$title = 'Order Details';

$pdo = db();
$id = (int)($_GET['id'] ?? 0);

/* Update status */
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $newStatus = $_POST['status'] ?? '';
    $notes     = trim($_POST['notes'] ?? '');
    if (in_array($newStatus, ['pending','paid','shipped','failed','cancelled'])) {
        $u = $pdo->prepare("UPDATE cosmetics_orders SET status = ?, notes = ? WHERE id = ?");
        $u->execute([$newStatus, $notes, $id]);
    }
    header("Location: admin-order-view.php?id=$id&saved=1");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM cosmetics_orders WHERE id = ?");
$stmt->execute([$id]);
$o = $stmt->fetch();
if (!$o) { header('Location: admin-orders.php'); exit; }

$itemsStmt = $pdo->prepare("SELECT * FROM cosmetics_order_items WHERE order_id = ?");
$itemsStmt->execute([$id]);
$items = $itemsStmt->fetchAll();

include __DIR__ . '/admin-layout.php';
?>

<?php if (!empty($_GET['saved'])): ?>
  <div class="admin-alert" style="background:#e8f5e9;color:#2e7d32;">✓ Order updated.</div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:24px;">
  <div>
    <div style="background:#fff;padding:24px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.04);margin-bottom:20px;">
      <h3 style="margin-bottom:16px;">Order <?= e($o['reference']) ?></h3>
      <p><strong>Customer:</strong> <?= e($o['customer_name']) ?></p>
      <p><strong>Phone:</strong> <?= e($o['phone']) ?></p>
      <p><strong>Email:</strong> <?= e($o['email']) ?></p>
      <p><strong>Delivery:</strong> <?= e($o['county']) ?> — <?= e($o['address']) ?></p>
      <p><strong>Payment:</strong> <?= strtoupper(e($o['payment_method'])) ?></p>
      <?php if ($o['mpesa_receipt']): ?>
        <p><strong>M-Pesa Receipt:</strong> <code><?= e($o['mpesa_receipt']) ?></code></p>
      <?php endif; ?>
      <p><strong>Status:</strong> <span class="badge badge--<?= e($o['status']) ?>"><?= e($o['status']) ?></span></p>
      <p style="color:#6b6b6b;font-size:13px;margin-top:10px;">Placed <?= date('d M Y, H:i', strtotime($o['created_at'])) ?></p>
    </div>

    <div style="background:#fff;padding:24px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,.04);">
      <h3 style="margin-bottom:14px;">Items</h3>
      <table class="admin-table">
        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
          <?php foreach ($items as $it): ?>
            <tr>
              <td><?= e($it['product_name']) ?></td>
              <td>KSh <?= number_format($it['price']) ?></td>
              <td><?= (int)$it['qty'] ?></td>
              <td><strong>KSh <?= number_format($it['price'] * $it['qty']) ?></strong></td>
            </tr>
          <?php endforeach; ?>
          <tr style="border-top:2px solid #1f1f1f;">
            <td colspan="3" style="text-align:right;">Subtotal</td>
            <td>KSh <?= number_format($o['subtotal']) ?></td>
          </tr>
          <tr>
            <td colspan="3" style="text-align:right;">Delivery</td>
            <td>KSh <?= number_format($o['delivery_fee']) ?></td>
          </tr>
          <tr>
            <td colspan="3" style="text-align:right;font-weight:700;">Total</td>
            <td><strong style="color:#e91e63;font-size:16px;">KSh <?= number_format($o['total']) ?></strong></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div>
    <form class="admin-form" method="post" style="padding:24px;">
      <h3 style="margin-bottom:14px;">Update Order</h3>

      <label>Status
        <select name="status">
          <?php foreach (['pending','paid','shipped','failed','cancelled'] as $s): ?>
            <option value="<?= $s ?>" <?= $o['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
          <?php endforeach; ?>
        </select>
      </label>

      <label>Internal Notes
        <textarea name="notes" rows="5"><?= e($o['notes'] ?? '') ?></textarea>
      </label>

      <button type="submit" class="admin-btn admin-btn--primary">Save</button>
      <a href="admin-orders.php" class="admin-btn admin-btn--ghost">Back to Orders</a>
    </form>
  </div>
</div>

<?php include __DIR__ . '/admin-footer.php'; ?>