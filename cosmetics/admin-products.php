<?php
require_once __DIR__ . '/admin-auth.php';
$title = 'Products';

$pdo = db();

/* Delete */
if (($_GET['action'] ?? '') === 'delete' && !empty($_GET['id'])) {
    $pdo->prepare("DELETE FROM cosmetics_products WHERE id = ?")->execute([$_GET['id']]);
    header('Location: admin-products.php?msg=deleted');
    exit;
}

$products = $pdo->query("SELECT * FROM cosmetics_products ORDER BY created_at DESC")->fetchAll();

include __DIR__ . '/admin-layout.php';
?>

<?php if (!empty($_GET['msg'])): ?>
  <div class="admin-alert admin-alert--error" style="background:#e8f5e9;color:#2e7d32;">
    ✓ Product <?= e($_GET['msg']) ?>.
  </div>
<?php endif; ?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
  <span style="color:#6b6b6b;"><?= count($products) ?> product(s)</span>
  <a href="admin-product-edit.php" class="admin-btn admin-btn--primary">+ Add Product</a>
</div>

<table class="admin-table">
  <thead>
    <tr>
      <th></th>
      <th>Name</th>
      <th>Brand</th>
      <th>Category</th>
      <th>Price</th>
      <th>Stock</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($products as $p): ?>
      <tr>
        <td style="font-size:24px;"><?= $p['emoji'] ?></td>
        <td><strong><?= e($p['name']) ?></strong><br><small style="color:#6b6b6b;"><?= e($p['id']) ?></small></td>
        <td><?= e($p['brand']) ?></td>
        <td><?= e($p['category']) ?></td>
        <td>KSh <?= number_format($p['price']) ?></td>
        <td><?= $p['in_stock'] ? '✓' : '✗' ?></td>
        <td style="text-align:right;white-space:nowrap;">
          <a href="admin-product-edit.php?id=<?= urlencode($p['id']) ?>" class="admin-btn admin-btn--ghost admin-btn--sm">Edit</a>
          <a href="admin-products.php?action=delete&id=<?= urlencode($p['id']) ?>" class="admin-btn admin-btn--danger admin-btn--sm"
             onclick="return confirm('Delete this product?')">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include __DIR__ . '/admin-footer.php'; ?>