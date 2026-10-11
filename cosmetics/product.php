<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/products.php';

$id      = trim((string)($_GET['id'] ?? ''));
$product = $id !== '' ? find_product($id) : null;

if (!$product) {
    redirect('category?slug=all');
}

$page_title = $product['name'] . ' — ' . SITE_NAME;
$extra_js   = ['product.js'];
include __DIR__ . '/header.php';

/* Related */
$related = array_values(array_filter(
    get_products(),
    fn($p) => $p['id'] !== $product['id'] && $p['category'] === $product['category']
));
if (count($related) < 4) {
    $extra   = array_filter(get_products(), fn($p) => $p['id'] !== $product['id']);
    $related = array_slice(array_merge($related, $extra), 0, 4);
}
$pid = (string)$product['id'];
?>

<nav class="breadcrumbs container" aria-label="Breadcrumb">
  <a href="<?= e(url('index')) ?>"><?= e(t('prod_breadcrumb_home')) ?></a> <span>/</span>
  <a href="<?= e(url('category?slug=' . urlencode($product['category']))) ?>"><?= e($product['category']) ?></a> <span>/</span>
  <span><?= e($product['name']) ?></span>
</nav>

<section class="product container">
  <div class="product__gallery">
    <div class="product__hero-img">
      <?php if (!empty($product['image_url'])): ?>
        <img src="<?= e($product['image_url']) ?>" alt="<?= e($product['name']) ?>" class="product__hero-image" />
      <?php else: ?>
        <?= $product['emoji'] ?>
      <?php endif; ?>
    </div>
    <div class="product__thumbs">
      <div class="product__thumb active">🔍</div>
      <div class="product__thumb">🌿</div>
      <div class="product__thumb">💧</div>
      <div class="product__thumb">✨</div>
    </div>
  </div>

  <div class="product__info">
    <p class="product__brand"><?= e($product['brand']) ?></p>
    <h1 class="product__title"><?= e($product['name']) ?></h1>

    <div class="product__rating">
      <span class="product__stars"><?= str_repeat('★', round($product['rating'])) ?></span>
      <span><?= e($product['rating']) ?> (<?= sprintf(e(t('prod_rating_reviews')), (int)$product['reviews']) ?>)</span>
    </div>

    <div class="product__price-block">
      <span class="product__price"><?= money($product['price']) ?></span>
      <?php if (!empty($product['old'])): ?>
        <span class="product__old"><?= money($product['old']) ?></span>
      <?php endif; ?>
      <?php if (!empty($product['discount'])): ?>
        <span class="product__discount"><?= e($product['discount']) ?></span>
      <?php endif; ?>
    </div>

    <p class="product__stock"><?= $product['in_stock'] ? e(t('prod_in_stock')) : e(t('prod_out_of_stock')) ?></p>

    <form action="<?= e(url('cart')) ?>" method="post" class="product__form">
      <input type="hidden" name="action" value="add" />
      <input type="hidden" name="id"     value="<?= e($pid) ?>" />
      <input type="hidden" name="qty"    id="qtyInput" value="1" />

      <div class="product__options">
        <div class="option-group">
          <span class="option-group__label"><?= e(t('prod_size')) ?></span>
          <div class="option-chips">
            <button type="button" class="option-chip active" data-size="<?= e($product['size']) ?>"><?= e($product['size']) ?></button>
          </div>
        </div>
        <?php if (!empty($product['skin_type'])): ?>
          <div class="option-group">
            <span class="option-group__label"><?= e(t('prod_suitable_for')) ?></span>
            <div class="option-chips">
              <?php foreach ($product['skin_type'] as $st): ?>
                <span class="option-chip" style="pointer-events:none;"><?= e($st) ?> <?= e(t('prod_skin_suffix')) ?></span>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <div class="product__actions">
        <div class="qty">
          <button type="button" class="qty__btn" data-qty="dec" aria-label="Decrease">−</button>
          <span class="qty__value" id="qtyValue">1</span>
          <button type="button" class="qty__btn" data-qty="inc" aria-label="Increase">+</button>
        </div>
        <button class="btn btn--primary product__add" type="submit"><?= e(t('prod_add_to_cart')) ?></button>
        <button type="button" class="product__wish" id="wishBtn">♡ <?= e(t('prod_wishlist')) ?></button>
      </div>
    </form>

    <?php if (!empty($product['benefits'])): ?>
      <ul class="product__benefits">
        <h4><?= e(t('prod_key_benefits')) ?></h4>
        <?php foreach ($product['benefits'] as $b): ?>
          <li><?= e($b) ?></li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>

    <div class="product__tabs">
      <div class="tabs-nav">
        <button class="tab-btn active" data-tab="desc"><?= e(t('prod_desc')) ?></button>
        <button class="tab-btn" data-tab="ingr"><?= e(t('prod_ingr')) ?></button>
        <button class="tab-btn" data-tab="how"><?= e(t('prod_how')) ?></button>
        <button class="tab-btn" data-tab="ship"><?= e(t('prod_ship')) ?></button>
      </div>

      <div class="tab-panel active" data-panel="desc"><p><?= e($product['description']) ?></p></div>
      <div class="tab-panel" data-panel="ingr"><p><?= e($product['ingredients']) ?></p></div>
      <div class="tab-panel" data-panel="how"><p><?= e($product['how_to_use']) ?></p></div>
      <div class="tab-panel" data-panel="ship">
        <h4><?= e(t('prod_delivery')) ?></h4>
        <p><?= e(t('prod_delivery_info')) ?></p>
        <h4><?= e(t('prod_returns')) ?></h4>
        <p><?= e(t('prod_returns_info')) ?></p>
      </div>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <header class="section__head">
      <p class="section__eyebrow"><?= e(t('prod_related_eyebrow')) ?></p>
      <h2 class="section__title"><?= e(t('prod_related_title')) ?></h2>
    </header>
    <div class="products-grid products-grid--related">
      <?php foreach (array_slice($related, 0, 4) as $r): ?>
        <?php $rid = (string)$r['id']; ?>
        <article class="product-card">
          <a href="<?= e(url('product?id=' . urlencode($rid))) ?>" class="product-card__media">
            <?php if (!empty($r['image_url'])): ?>
              <img src="<?= e($r['image_url']) ?>" alt="<?= e($r['name']) ?>" class="product-card__img" loading="lazy" />
            <?php else: ?>
              <span aria-hidden="true"><?= $r['emoji'] ?></span>
            <?php endif; ?>
            <?php if (!empty($r['discount'])): ?>
              <span class="product-card__discount"><?= e($r['discount']) ?></span>
            <?php endif; ?>
          </a>
          <div class="product-card__body">
            <span class="product-card__brand"><?= e($r['brand']) ?></span>
            <h3 class="product-card__name"><?= e($r['name']) ?></h3>
            <div class="product-card__price">
              <span class="now"><?= money($r['price']) ?></span>
              <?php if (!empty($r['old'])): ?>
                <span class="was"><?= money($r['old']) ?></span>
              <?php endif; ?>
            </div>
            <a href="<?= e(url('product?id=' . urlencode($rid))) ?>" class="product-card__add" style="text-align:center;">
              <?= e(t('prod_view_product')) ?>
            </a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>