<?php
/* =========================================================
   cart.php — full cart with DB-backed product lookup
   Products use STRING ids (varchar(80), e.g. "gucci-bloom-edp")
   ========================================================= */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

/* ---------------------------------------------------------
   Read action (POST first, then GET)
   --------------------------------------------------------- */
$action = $_POST['action'] ?? $_GET['action'] ?? null;

/* ---------------------------------------------------------
   --- Add single product ---
   NOTE: id is a string slug, not an integer.
   --------------------------------------------------------- */
if ($action === 'add') {
    $id  = trim((string)($_POST['id']  ?? $_GET['id']  ?? ''));
    $qty = max(1, (int)($_POST['qty'] ?? $_GET['qty'] ?? 1));

    $p = null;
    if ($id !== '') {
        $stmt = db()->prepare("SELECT * FROM cosmetics_products WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $p = $stmt->fetch() ?: null;
    }

    if ($p) {
        $_SESSION['cart'] ??= [];
        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            // Compare as strings
            if ((string)$item['id'] === (string)$p['id']) {
                $item['qty'] += $qty;
                $found = true;
                break;
            }
        }
        unset($item);

        if (!$found) {
            $_SESSION['cart'][] = [
                'id'        => (string)$p['id'],
                'name'      => $p['name'],
                'brand'     => $p['brand'] ?? '',
                'price'     => (float)$p['price'],
                'emoji'     => $p['emoji'] ?? '🛍️',
                'image_url' => $p['image_url'] ?? null,
                'qty'       => $qty,
            ];
        }
        flash('success', $p['name'] . ' ✓');
    } else {
        flash('error', 'Product not found.');
    }

    $back = $_SERVER['HTTP_REFERER'] ?? url('cart');
    redirect($back);
}

/* ---------------------------------------------------------
   --- Add bundle ---
   --------------------------------------------------------- */
if ($action === 'add_bundle') {
    $name  = $_POST['name']  ?? 'Bundle';
    $price = (int)($_POST['price'] ?? 0);

    $_SESSION['cart'] ??= [];
    $_SESSION['cart'][] = [
        'id'        => 'bundle-' . md5($name),
        'name'      => $name,
        'brand'     => 'Bundle',
        'price'     => $price,
        'emoji'     => '🎁',
        'image_url' => null,
        'qty'       => 1,
    ];
    flash('success', $name . ' ✓');

    $back = $_SERVER['HTTP_REFERER'] ?? url('cart');
    redirect($back);
}

/* ---------------------------------------------------------
   --- Remove item ---
   --------------------------------------------------------- */
if ($action === 'remove') {
    $index = (int)($_GET['index'] ?? -1);
    if (isset($_SESSION['cart'][$index])) {
        array_splice($_SESSION['cart'], $index, 1);
    }
    redirect(url('cart'));
}

/* ---------------------------------------------------------
   --- Update qty ---
   --------------------------------------------------------- */
if ($action === 'update') {
    $index = (int)($_POST['index'] ?? -1);
    $qty   = max(1, (int)($_POST['qty'] ?? 1));
    if (isset($_SESSION['cart'][$index])) {
        $_SESSION['cart'][$index]['qty'] = $qty;
    }
    redirect(url('cart'));
}

/* ---------------------------------------------------------
   --- Clear cart ---
   --------------------------------------------------------- */
if ($action === 'clear') {
    $_SESSION['cart'] = [];
    redirect(url('cart'));
}

/* ---------------------------------------------------------
   Render
   --------------------------------------------------------- */
$page_title = t('your_cart') . ' — ' . SITE_NAME;
include __DIR__ . '/header.php';

$cart = $_SESSION['cart'] ?? [];
$subtotal = 0;
foreach ($cart as $it) {
    $subtotal += $it['price'] * $it['qty'];
}
$delivery   = ($subtotal >= FREE_SHIP_THRESHOLD || $subtotal === 0) ? 0 : 250;
$grandTotal = $subtotal + $delivery;
?>

<section class="section">
  <div class="container">
    <header class="section__head">
      <p class="section__eyebrow"><?= e(t('shop')) ?></p>
      <h2 class="section__title"><?= e(t('your_cart')) ?></h2>
      <p class="section__subtitle">
        <?= count($cart) ?> <?= e(t('items_in_cart')) ?>
      </p>
    </header>

    <?php if ($msg = flash('success')): ?>
      <p style="text-align:center;color:#2e7d32;font-weight:500;margin-bottom:20px;">
        ✓ <?= e($msg) ?>
      </p>
    <?php endif; ?>

    <?php if ($msg = flash('error')): ?>
      <p style="text-align:center;color:#c2185b;font-weight:500;margin-bottom:20px;">
        ⚠ <?= e($msg) ?>
      </p>
    <?php endif; ?>

    <?php if (empty($cart)): ?>
      <div style="text-align:center;padding:60px 0;">
        <p style="font-size:60px;margin-bottom:16px;">🛒</p>
        <p style="color:#6b6b6b;margin-bottom:24px;"><?= e(t('cart_empty')) ?></p>
        <a href="<?= e(url('category?slug=all')) ?>" class="btn btn--primary"><?= e(t('start_shopping')) ?></a>
      </div>
    <?php else: ?>
      <div class="cart-layout">
        <div class="cart-list">
          <?php foreach ($cart as $i => $item): ?>
            <div class="cart-row">
              <div class="cart-row__media">
                <?php if (!empty($item['image_url'])): ?>
                  <img src="<?= e($item['image_url']) ?>"
                       alt="<?= e($item['name']) ?>"
                       class="cart-row__img"
                       loading="lazy" />
                <?php else: ?>
                  <?= $item['emoji'] ?>
                <?php endif; ?>
              </div>

              <div class="cart-row__info">
                <span class="cart-row__brand"><?= e($item['brand']) ?></span>
                <h3 class="cart-row__name"><?= e($item['name']) ?></h3>
                <span class="cart-row__price"><?= money($item['price']) ?></span>
              </div>

              <form action="<?= e(url('cart')) ?>" method="post" class="cart-row__qty">
                <input type="hidden" name="action" value="update" />
                <input type="hidden" name="index" value="<?= $i ?>" />
                <input type="number" name="qty" value="<?= (int)$item['qty'] ?>" min="1" />
                <button type="submit" class="cart-row__update"><?= e(t('save')) ?></button>
              </form>

              <div class="cart-row__subtotal">
                <?= money($item['price'] * $item['qty']) ?>
              </div>

              <a href="<?= e(url('cart?action=remove&index=' . $i)) ?>"
                 class="cart-row__remove"
                 aria-label="Remove">×</a>
            </div>
          <?php endforeach; ?>

          <div style="margin-top:20px;display:flex;gap:12px;flex-wrap:wrap;">
            <a href="<?= e(url('category?slug=all')) ?>" class="btn btn--ghost">
              <?= e(t('continue_shopping')) ?>
            </a>
            <a href="<?= e(url('cart?action=clear')) ?>"
               class="btn btn--ghost"
               style="color:#c2185b;border-color:#c2185b;">
              <?= e(t('clear_cart')) ?>
            </a>
          </div>
        </div>

        <aside class="cart-summary">
          <h3><?= e(t('order_summary')) ?></h3>

          <div class="cart-summary__row">
            <span><?= e(t('subtotal')) ?></span>
            <strong><?= money($subtotal) ?></strong>
          </div>

          <div class="cart-summary__row">
            <span><?= e(t('delivery')) ?></span>
            <strong><?= $delivery === 0 ? e(t('free')) : money($delivery) ?></strong>
          </div>

          <div class="cart-summary__row cart-summary__row--total">
            <span><?= e(t('total')) ?></span>
            <strong><?= money($grandTotal) ?></strong>
          </div>

          <a href="<?= e(url('checkout')) ?>" class="btn btn--primary btn--full">
            <?= e(t('checkout')) ?>
          </a>
        </aside>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>