<?php
/* =========================================================
   checkout.php — i18n + safe loading + optional M-Pesa
   ========================================================= */

require_once __DIR__ . '/config.php';   // session + lang + helpers
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/products.php';

/* M-Pesa is optional — only load if the file exists */
$mpesaFile = __DIR__ . '/mpesa.php';
$hasMpesa  = file_exists($mpesaFile);
if ($hasMpesa) {
    require_once $mpesaFile;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    redirect('cart');
}

$page_title = t('checkout') . ' — ' . SITE_NAME;
$extra_js   = ['checkout.js'];

$subtotal = cart_total();
$delivery = $subtotal >= FREE_SHIP_THRESHOLD ? 0 : 250;
$total    = $subtotal + $delivery;

$error = null;

/* ---------------------------------------------------------
   Handle order placement
   --------------------------------------------------------- */
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'
    && ($_POST['action'] ?? '') === 'place_order') {

    try {
        $name    = trim($_POST['name']    ?? '');
        $phone   = trim($_POST['phone']   ?? '');
        $email   = trim($_POST['email']   ?? '');
        $county  = trim($_POST['county']  ?? '');
        $address = trim($_POST['address'] ?? '');
        $payment = $_POST['payment'] ?? 'mpesa';

        if (!$name || !$phone || !$email || !$county || !$address) {
            throw new RuntimeException(t('contact_error'));
        }

        $reference = 'GB-' . strtoupper(bin2hex(random_bytes(4)));

        $pdo = db();
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("
            INSERT INTO cosmetics_orders
              (reference, customer_name, phone, email, county, address,
               payment_method, subtotal, delivery_fee, total, status)
            VALUES (?,?,?,?,?,?,?,?,?,?, 'pending')
        ");
        $stmt->execute([
            $reference, $name, $phone, $email, $county, $address,
            $payment, $subtotal, $delivery, $total
        ]);
        $orderId = (int)$pdo->lastInsertId();

        $itemStmt = $pdo->prepare("
            INSERT INTO cosmetics_order_items
              (order_id, product_id, product_name, price, qty)
            VALUES (?,?,?,?,?)
        ");
        foreach ($cart as $item) {
            $itemStmt->execute([
                $orderId,
                (string)$item['id'],
                $item['name'],
                (float)$item['price'],
                (int)$item['qty']
            ]);
        }

        $pdo->commit();

        /* M-Pesa push — only if customer chose M-Pesa AND mpesa.php is loaded */
        if ($payment === 'mpesa' && $hasMpesa && class_exists('Mpesa')) {
            try {
                $mpesa = new Mpesa();
                $res   = $mpesa->stkPush($phone, $total, $reference, 'GlowBeauty order');

                $checkoutId = $res['CheckoutRequestID'] ?? null;
                if ($checkoutId) {
                    $u = $pdo->prepare("UPDATE cosmetics_orders SET mpesa_checkout_id = ? WHERE id = ?");
                    $u->execute([$checkoutId, $orderId]);
                } else {
                    // Log but don't block the order
                    error_log('M-Pesa push failed for order ' . $reference . ': ' . json_encode($res));
                }
            } catch (Throwable $mpesaEx) {
                // M-Pesa failure must not roll back the whole order
                error_log('M-Pesa exception for order ' . $reference . ': ' . $mpesaEx->getMessage());
            }
        }

        $_SESSION['last_order_id'] = $orderId;
        $_SESSION['cart'] = [];

        redirect('order-success?id=' . $orderId);

    } catch (Throwable $ex) {
        if (db()->inTransaction()) db()->rollBack();
        $error = $ex->getMessage();
    }
}

include __DIR__ . '/header.php';
?>

<section class="section">
  <div class="container">
    <header class="section__head">
      <p class="section__eyebrow"><?= e(t('almost_there')) ?></p>
      <h2 class="section__title"><?= e(t('checkout')) ?></h2>
    </header>

    <?php if ($error): ?>
      <p style="text-align:center;color:#c2185b;font-weight:500;margin-bottom:20px;">
        ⚠ <?= e($error) ?>
      </p>
    <?php endif; ?>

    <div class="checkout-layout">
      <form class="checkout-form" id="checkoutForm" action="<?= e(url('checkout')) ?>" method="post">
        <input type="hidden" name="action" value="place_order" />

        <h3><?= e(t('contact_info')) ?></h3>
        <div class="form-row">
          <label><?= e(t('full_name')) ?>
            <input type="text" name="name" required />
          </label>
          <label><?= e(t('phone')) ?>
            <input type="tel" name="phone" placeholder="07XX XXX XXX" required />
          </label>
        </div>
        <label><?= e(t('email')) ?>
          <input type="email" name="email" required />
        </label>

        <h3><?= e(t('delivery_address')) ?></h3>
        <label><?= e(t('county')) ?>
          <select name="county" required>
            <option value=""><?= e(t('select_county')) ?></option>
            <?php foreach (['Nairobi','Kiambu','Mombasa','Kisumu','Nakuru','Eldoret','Machakos','Other'] as $c): ?>
              <option><?= e($c) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label><?= e(t('street_area')) ?>
          <input type="text" name="address" required />
        </label>

        <h3><?= e(t('payment')) ?></h3>
        <div class="payment-options">
          <label class="payment-option">
            <input type="radio" name="payment" value="mpesa" checked />
            <span>
              <strong><?= e(t('mpesa')) ?></strong>
              <small><?= e(t('mpesa_sub')) ?></small>
            </span>
          </label>
          <label class="payment-option">
            <input type="radio" name="payment" value="cod" />
            <span>
              <strong><?= e(t('cod')) ?></strong>
              <small><?= e(t('cod_sub')) ?></small>
            </span>
          </label>
        </div>

        <button class="btn btn--primary btn--full" type="submit">
          <?= e(t('place_order')) ?> · <?= money($total) ?>
        </button>
      </form>

      <aside class="order-summary">
        <h3><?= e(t('order_summary')) ?></h3>

        <div class="order-items">
          <?php foreach ($cart as $item): ?>
            <div class="order-item">
              <div class="order-item__media">
                <?php if (!empty($item['image_url'])): ?>
                  <img src="<?= e($item['image_url']) ?>"
                       alt="<?= e($item['name']) ?>"
                       class="order-item__img"
                       loading="lazy" />
                <?php else: ?>
                  <span aria-hidden="true"><?= $item['emoji'] ?></span>
                <?php endif; ?>
              </div>
              <div class="order-item__info">
                <h4 class="order-item__name"><?= e($item['name']) ?></h4>
                <p class="order-item__meta">
                  <?= e($item['brand']) ?> · ×<?= (int)$item['qty'] ?>
                </p>
              </div>
              <div class="order-item__price">
                <?= money($item['price'] * $item['qty']) ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="order-totals">
          <div class="order-line">
            <span><?= e(t('subtotal')) ?></span>
            <strong><?= money($subtotal) ?></strong>
          </div>
          <div class="order-line">
            <span><?= e(t('delivery')) ?></span>
            <strong><?= $delivery ? money($delivery) : e(t('free')) ?></strong>
          </div>
          <div class="order-line order-line--total">
            <span><?= e(t('total')) ?></span>
            <strong><?= money($total) ?></strong>
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>