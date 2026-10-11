<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/products.php';

$orderId = (int)($_GET['id'] ?? 0);
if (!$orderId) redirect('index');

$stmt = db()->prepare("SELECT * FROM cosmetics_orders WHERE id = ?");
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) redirect('index');

$itemStmt = db()->prepare("SELECT * FROM cosmetics_order_items WHERE order_id = ?");
$itemStmt->execute([$orderId]);
$items = $itemStmt->fetchAll();

$page_title = t('order_success_title');
include __DIR__ . '/header.php';
?>

<section class="section">
  <div class="container" style="max-width:760px;text-align:center;">
    <p style="font-size:70px;">🎉</p>
    <h2 class="section__title"><?= sprintf(e(t('order_thank_you')), e($order['customer_name'])) ?></h2>
    <p class="section__subtitle"><?= e(t('order_reference')) ?> <strong><?= e($order['reference']) ?></strong></p>

    <?php if ($order['payment_method'] === 'mpesa' && $order['status'] === 'pending'): ?>
      <p style="margin:24px 0;color:#6b6b6b;">
        <?= sprintf(e(t('order_mpesa_instruction')), '<strong>' . e($order['phone']) . '</strong>') ?>
      </p>
    <?php elseif ($order['status'] === 'paid'): ?>
      <p style="margin:24px 0;color:#2e7d32;font-weight:500;">
        <?= sprintf(e(t('order_payment_received')), e($order['mpesa_receipt'] ?? '—')) ?>
      </p>
    <?php else: ?>
      <p style="margin:24px 0;color:#6b6b6b;">
        <?= sprintf(e(t('order_cod_instruction')), e($order['county']), e($order['address'])) ?>
      </p>
    <?php endif; ?>

    <!-- =============================================
         TRACK + CANCEL ORDER BUTTONS
         ============================================= -->
    <div class="order-actions">
      <button type="button" class="btn btn--primary" id="trackOrderBtn">
        <?= e(t('order_track_btn')) ?>
      </button>
      <button type="button" class="btn btn--ghost order-actions__cancel" id="cancelOrderBtn" hidden>
        <?= e(t('order_cancel_btn')) ?>
      </button>
    </div>

    <!-- 10-second cancel countdown -->
    <div class="order-cancel-window" id="cancelWindow" hidden>
      <p><?= sprintf(t('order_cancel_countdown'), '<strong id="cancelCount">10</strong>') ?></p>
      <div class="order-cancel-window__bar">
        <div class="order-cancel-window__progress" id="cancelProgress"></div>
      </div>
    </div>

    <!-- Cancelled confirmation -->
    <div class="order-cancelled" id="cancelledBox" hidden>
      <p><?= e(t('order_cancelled_msg')) ?></p>
      <a href="<?= e(url('index')) ?>" class="btn btn--primary"><?= e(t('order_continue_shopping')) ?></a>
    </div>

    <!-- =============================================
         ORDER TRACKER (hidden until button click)
         ============================================= -->
    <div class="order-tracker" id="orderTracker" hidden>
      <div class="order-tracker__head">
        <h3><?= e(t('order_status_title')) ?></h3>
        <p id="trackerMessage"><?= e(t('order_status_starting')) ?></p>
      </div>

      <div class="order-tracker__bar">
        <div class="order-tracker__progress" id="trackerProgress"></div>
      </div>

      <ol class="order-tracker__steps">
        <li class="order-step" data-step="0">
          <span class="order-step__dot"></span>
          <span class="order-step__label"><?= e(t('order_status_step_0')) ?></span>
        </li>
        <li class="order-step" data-step="1">
          <span class="order-step__dot"></span>
          <span class="order-step__label"><?= e(t('order_status_step_1')) ?></span>
        </li>
        <li class="order-step" data-step="2">
          <span class="order-step__dot"></span>
          <span class="order-step__label"><?= e(t('order_status_step_2')) ?></span>
        </li>
        <li class="order-step" data-step="3">
          <span class="order-step__dot"></span>
          <span class="order-step__label"><?= e(t('order_status_step_3')) ?></span>
        </li>
      </ol>
    </div>

    <!-- =============================================
         ORDER DETAILS
         ============================================= -->
    <div style="text-align:left;background:#faf7f8;padding:24px;border-radius:14px;margin:30px 0;">
      <h3 style="font-family:var(--font-display);margin-bottom:14px;"><?= e(t('order_details_title')) ?></h3>
      <?php foreach ($items as $it): ?>
        <div class="order-line">
          <span><?= e($it['product_name']) ?> × <?= (int)$it['qty'] ?></span>
          <strong><?= money($it['price'] * $it['qty']) ?></strong>
        </div>
      <?php endforeach; ?>
      <div class="order-line"><span><?= e(t('order_subtotal')) ?></span><strong><?= money($order['subtotal']) ?></strong></div>
      <div class="order-line"><span><?= e(t('order_delivery')) ?></span><strong><?= $order['delivery_fee'] ? money($order['delivery_fee']) : e(t('order_free')) ?></strong></div>
      <div class="order-line order-line--total"><span><?= e(t('order_total')) ?></span><strong><?= money($order['total']) ?></strong></div>
    </div>

    <a href="<?= e(url('index')) ?>" class="btn btn--primary"><?= e(t('order_continue_shopping')) ?></a>
  </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>