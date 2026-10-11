<?php
require_once __DIR__ . '/admin-auth.php';

$pdo = db();
$id  = $_GET['id'] ?? null;
$isEdit = (bool)$id;
$title  = $isEdit ? 'Edit Product' : 'Add Product';

$p = [
    'id' => '', 'brand' => '', 'name' => '', 'category' => 'body-care',
    'price' => '', 'old_price' => '', 'rating' => '5.0', 'reviews' => 0,
    'emoji' => '🧴', 'discount' => '', 'size' => '',
    'skin_type' => '', 'concerns' => '', 'in_stock' => 1,
    'benefits' => '', 'description' => '', 'ingredients' => '', 'how_to_use' => '',
];

if ($isEdit) {
    $stmt = $pdo->prepare("SELECT * FROM cosmetics_products WHERE id = ?");
    $stmt->execute([$id]);
    $p = $stmt->fetch() ?: $p;
}

$error = null;

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $data = $_POST;
    try {
        if (!$isEdit) {
            $p['id'] = preg_replace('/[^a-z0-9-]+/', '-', strtolower($data['id'] ?: $data['name']));
            $stmt = $pdo->prepare("
              INSERT INTO cosmetics_products
                (id, brand, name, category, price, old_price, rating, reviews, emoji,
                 discount, size, skin_type, concerns, in_stock, benefits, description, ingredients, how_to_use)
              VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
            ");
            $stmt->execute([
                $p['id'], $data['brand'], $data['name'], $data['category'],
                (int)$data['price'], (int)$data['old_price'],
                (float)$data['rating'], (int)$data['reviews'],
                $data['emoji'], $data['discount'], $data['size'],
                $data['skin_type'], $data['concerns'],
                isset($data['in_stock']) ? 1 : 0,
                $data['benefits'], $data['description'], $data['ingredients'], $data['how_to_use'],
            ]);
        } else {
            $stmt = $pdo->prepare("
              UPDATE cosmetics_products SET
                brand=?, name=?, category=?, price=?, old_price=?, rating=?, reviews=?,
                emoji=?, discount=?, size=?, skin_type=?, concerns=?, in_stock=?,
                benefits=?, description=?, ingredients=?, how_to_use=?
              WHERE id=?
            ");
            $stmt->execute([
                $data['brand'], $data['name'], $data['category'],
                (int)$data['price'], (int)$data['old_price'],
                (float)$data['rating'], (int)$data['reviews'],
                $data['emoji'], $data['discount'], $data['size'],
                $data['skin_type'], $data['concerns'],
                isset($data['in_stock']) ? 1 : 0,
                $data['benefits'], $data['description'], $data['ingredients'], $data['how_to_use'],
                $id,
            ]);
        }
        header('Location: admin-products.php?msg=saved');
        exit;
    } catch (Throwable $ex) {
        $error = $ex->getMessage();
        $p = array_merge($p, $data);
    }
}

include __DIR__ . '/admin-layout.php';
?>

<?php if ($error): ?>
  <div class="admin-alert admin-alert--error"><?= e($error) ?></div>
<?php endif; ?>

<form class="admin-form" method="post">
  <?php if (!$isEdit): ?>
    <label>ID (slug, optional — auto-generated from name)
      <input type="text" name="id" value="<?= e($p['id']) ?>" placeholder="e.g. nivea-cocoa-lotion" />
    </label>
  <?php endif; ?>

  <div class="admin-form__row">
    <label>Brand <input type="text" name="brand" value="<?= e($p['brand']) ?>" required /></label>
    <label>Name  <input type="text" name="name"  value="<?= e($p['name'])  ?>" required /></label>
  </div>

  <div class="admin-form__row">
    <label>Category
      <select name="category" required>
        <?php foreach (['body-care','face-care','makeup','hair-care','fragrance','nails','bath','accessories'] as $c): ?>
          <option value="<?= $c ?>" <?= $p['category'] === $c ? 'selected' : '' ?>><?= $c ?></option>
        <?php endforeach; ?>
      </select>
    </label>
    <label>Emoji <input type="text" name="emoji" value="<?= e($p['emoji']) ?>" maxlength="4" /></label>
  </div>

  <div class="admin-form__row">
    <label>Price (KSh) <input type="number" name="price" value="<?= e($p['price']) ?>" required /></label>
    <label>Old Price (KSh) <input type="number" name="old_price" value="<?= e($p['old_price']) ?>" /></label>
  </div>

  <div class="admin-form__row">
    <label>Discount Label <input type="text" name="discount" value="<?= e($p['discount']) ?>" placeholder="20% OFF" /></label>
    <label>Size <input type="text" name="size" value="<?= e($p['size']) ?>" placeholder="400ml" /></label>
  </div>

  <div class="admin-form__row">
    <label>Rating <input type="number" name="rating" step="0.1" min="0" max="5" value="<?= e($p['rating']) ?>" /></label>
    <label>Reviews Count <input type="number" name="reviews" value="<?= e($p['reviews']) ?>" /></label>
  </div>

  <div class="admin-form__row">
    <label>Skin Types (comma-separated)
      <input type="text" name="skin_type" value="<?= e($p['skin_type']) ?>" placeholder="Dry,Normal" />
    </label>
    <label>Concerns (comma-separated)
      <input type="text" name="concerns" value="<?= e($p['concerns']) ?>" placeholder="hydration,glow" />
    </label>
  </div>

  <label>Benefits (one per line)
    <textarea name="benefits" rows="4"><?= e($p['benefits']) ?></textarea>
  </label>

  <label>Description
    <textarea name="description" rows="3"><?= e($p['description']) ?></textarea>
  </label>

  <label>Ingredients
    <textarea name="ingredients" rows="2"><?= e($p['ingredients']) ?></textarea>
  </label>

  <label>How to Use
    <textarea name="how_to_use" rows="2"><?= e($p['how_to_use']) ?></textarea>
  </label>

  <label style="display:flex;align-items:center;gap:8px;">
    <input type="checkbox" name="in_stock" value="1" <?= $p['in_stock'] ? 'checked' : '' ?> style="width:auto;margin:0;" />
    In stock
  </label>

  <div style="display:flex;gap:10px;">
    <button type="submit" class="admin-btn admin-btn--primary"><?= $isEdit ? 'Save Changes' : 'Create Product' ?></button>
    <a href="admin-products.php" class="admin-btn admin-btn--ghost">Cancel</a>
  </div>
</form>

<?php include __DIR__ . '/admin-footer.php'; ?>