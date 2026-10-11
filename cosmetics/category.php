<?php
/* =========================================================
   category.php — loads products directly from the database
   ========================================================= */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

/* ---------------------------------------------------------
   Read query parameters
   --------------------------------------------------------- */
$slug    = $_GET['slug']    ?? 'all';
$q       = trim($_GET['q']       ?? '');
$skin    = trim($_GET['skin']    ?? '');
$concern = trim($_GET['concern'] ?? '');

/* ---------------------------------------------------------
   Category labels
   --------------------------------------------------------- */
$categories = [
    'body-care'   => ['label' => t('body_care'),     'icon' => '🧴', 'desc' => t('cat_desc_body')],
    'face-care'   => ['label' => t('face_care'),     'icon' => '✨', 'desc' => t('cat_desc_face')],
    'makeup'      => ['label' => t('makeup'),        'icon' => '💄', 'desc' => t('cat_desc_makeup')],
    'hair-care'   => ['label' => t('hair_care'),     'icon' => '💇‍♀️', 'desc' => t('cat_desc_hair')],
    'fragrance'   => ['label' => t('fragrance'),     'icon' => '🌸', 'desc' => t('cat_desc_fragrance')],
    'nails'       => ['label' => t('nails'),         'icon' => '💅', 'desc' => t('cat_desc_nails')],
    'bath'        => ['label' => t('bath_selfcare'), 'icon' => '🛁', 'desc' => t('cat_desc_bath')],
    'accessories' => ['label' => t('accessories'),   'icon' => '🎁', 'desc' => t('cat_desc_accessories')],
];

$label      = $categories[$slug]['label'] ?? t('cat_all_products');
$page_title = $label . ' — ' . SITE_NAME;

/* ---------------------------------------------------------
   Build query
   --------------------------------------------------------- */
$sql    = "SELECT * FROM cosmetics_products WHERE 1=1";
$params = [];

if ($slug && $slug !== 'all') {
    $sql .= " AND category = ?";
    $params[] = $slug;
}

if ($q !== '') {
    $sql .= " AND (name LIKE ? OR brand LIKE ? OR description LIKE ?)";
    $like = '%' . $q . '%';
    $params[] = $like;
    $params[] = $like;
    $params[] = $like;
}

if ($skin !== '') {
    $sql .= " AND LOWER(skin_type) LIKE ?";
    $params[] = '%' . strtolower($skin) . '%';
}

if ($concern !== '') {
    $sql .= " AND LOWER(concerns) LIKE ?";
    $params[] = '%' . strtolower($concern) . '%';
}

$sql .= " ORDER BY created_at DESC";

$stmt  = db()->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();

/* ---------------------------------------------------------
   Render
   --------------------------------------------------------- */
include __DIR__ . '/header.php';
?>

<section class="section">
  <div class="container">
    <header class="section__head">
      <p class="section__eyebrow"><?= e(t('cat_shop_eyebrow')) ?></p>
      <h2 class="section__title"><?= e($label) ?></h2>
      <p class="section__subtitle"><?= sprintf(e(t('cat_products_found')), count($items)) ?></p>
    </header>

    <?php if (!empty($items)): ?>
      <div class="products-grid">
        <?php foreach ($items as $p): ?>
          <?php $pid = (string)($p['id'] ?? ''); ?>
          <article class="product-card">
            <a href="<?= e(url('product?id=' . urlencode($pid))) ?>" class="product-card__media">
              <?php if (!empty($p['image_url'])): ?>
                <img src="<?= e($p['image_url']) ?>"
                     alt="<?= e($p['name']) ?>"
                     class="product-card__img"
                     loading="lazy" />
              <?php else: ?>
                <span aria-hidden="true"><?= e($p['emoji'] ?? '🛍️') ?></span>
              <?php endif; ?>
              <?php if (!empty($p['discount'])): ?>
                <span class="product-card__discount"><?= e($p['discount']) ?></span>
              <?php endif; ?>
            </a>

            <div class="product-card__body">
              <span class="product-card__brand"><?= e($p['brand'] ?? '') ?></span>
              <h3 class="product-card__name"><?= e($p['name']) ?></h3>
              <div class="product-card__rating"><span>★</span><?= e($p['rating'] ?? '5.0') ?></div>
              <div class="product-card__price">
                <span class="now"><?= money($p['price']) ?></span>
                <?php if (!empty($p['old_price'])): ?>
                  <span class="was"><?= money($p['old_price']) ?></span>
                <?php endif; ?>
              </div>

              <!-- Add to cart (POST) — id is a STRING, keep it as-is -->
              <form action="<?= e(url('cart')) ?>" method="post">
                <input type="hidden" name="action" value="add" />
                <input type="hidden" name="id"     value="<?= e($pid) ?>" />
                <input type="hidden" name="qty"    value="1" />
                <button class="product-card__add" type="submit"><?= e(t('add_to_cart')) ?></button>
              </form>

              <a href="<?= e(url('product?id=' . urlencode($pid))) ?>" class="product-card__detail">
                <?= e(t('cat_see_detail')) ?>
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p style="text-align:center;color:#6b6b6b;padding:40px 0;">
        <?= e(t('cat_no_products')) ?>
        <a href="<?= e(url('category?slug=all')) ?>" style="color:#e91e63;"><?= e(t('cat_view_all')) ?></a>.
      </p>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>