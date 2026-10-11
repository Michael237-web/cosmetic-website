<?php
// --- TEMPORARY DEBUGGING (must be first) ---
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php_errors.log');
// -------------------------------------------

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/products.php';

$page_title = t('site_name') . ' — Premium Cosmetics in Kenya';
include __DIR__ . '/header.php';

$trending = array_slice(get_products(), 0, 8);
$categories = get_categories();
?>

<section class="hero" id="home">
  <!-- Slideshow background layers — each slide has a unique entrance animation -->
  <div class="hero__slideshow" aria-hidden="true">
    <div class="hero__slide slide-top"     style="background-image: url('https://s7ap1.scene7.com/is/image/krisshop/re-nutriv-1190x790?fmt=webp');"></div>
    <div class="hero__slide slide-left"    style="background-image: url('https://images.unsplash.com/photo-1779056904689-ff99fd0045a4?auto=format&fit=crop&w=1920&q=85');"></div>
    <div class="hero__slide slide-bottom"  style="background-image: url('https://images.unsplash.com/photo-1764581218410-303283f5fd9c?auto=format&fit=crop&w=1920&q=85');"></div>
    <div class="hero__slide slide-right"   style="background-image: url('https://images.unsplash.com/photo-1768483018807-bd0b9ab86539?auto=format&fit=crop&w=1920&q=85');"></div>
    <div class="hero__slide slide-zoom"    style="background-image: url('https://images.unsplash.com/photo-1772429378994-15c1e8568085?auto=format&fit=crop&w=1920&q=85');"></div>
    <div class="hero__slide slide-fade"    style="background-image: url('https://images.pexels.com/photos/7317071/pexels-photo-7317071.jpeg?auto=compress&cs=tinysrgb&w=1920');"></div>
    <div class="hero__slide slide-rotate"  style="background-image: url('https://images.unsplash.com/photo-1772429378994-15c1e8568085?auto=format&fit=crop&w=1920&q=85');"></div>
    <div class="hero__slide slide-popup"   style="background-image: url('https://cdn.shopify.com/s/files/1/0861/5280/6704/files/Rectangle_2_ed9454fa-aea8-4f09-9644-76c874cbd52d.png?v=1775288304');"></div>
    <div class="hero__slide slide-blur"    style="background-image: url('https://a2cosmetica.com.br/assets/hero-banner-CJWj46Ut.jpg');"></div>
    <div class="hero__slide slide-skew"    style="background-image: url('https://myseasidespa.com/cdn/shop/files/face-care.png?v=1764000224&width=1248');"></div>
    <div class="hero__slide slide-flip"    style="background-image: url('https://images.ctfassets.net/wlke2cbybljx/6rjWMpKO3IImscieKl6UW6/5400a45b1a4b7068d6b3640f21a625fc/4x3_WONS_Family_Still_Life.jpg');"></div>
    <div class="hero__slide slide-scale"   style="background-image: url('https://cdn.notinoimg.com/detail_main_hq/estee-lauder/887167681293_03/double-wear-stay-in-place-concealer___250429.jpg');"></div>
  </div>

  <div class="container hero__inner">
    <div class="hero__content">
      <p class="hero__eyebrow"><?= e(t('hero_eyebrow')) ?></p>
      <h1 class="hero__title">
        <?= e(t('hero_title_1')) ?> <span><?= e(t('hero_title_2')) ?></span>. <?= e(t('hero_title_3')) ?>
      </h1>
      <p class="hero__subtitle"><?= e(t('hero_subtitle')) ?></p>
      <div class="hero__actions">
        <a href="#categories" class="btn btn--primary"><?= e(t('shop_now')) ?></a>
        <a href="category.php?slug=all" class="btn btn--ghost"><?= e(t('browse_all')) ?></a>
      </div>
      <ul class="hero__badges">
        <li>✓ <?= e(t('badge_authentic')) ?></li>
        <li>✓ <?= e(t('badge_fast')) ?></li>
        <li>✓ <?= e(t('badge_returns')) ?></li>
      </ul>
    </div>
  </div>

  <!-- Slideshow dots -->
  <div class="hero__dots" role="tablist" aria-label="Slideshow navigation">
    <button class="hero__dot is-active" data-slide="0"  aria-label="Slide 1"></button>
    <button class="hero__dot"           data-slide="1"  aria-label="Slide 2"></button>
    <button class="hero__dot"           data-slide="2"  aria-label="Slide 3"></button>
    <button class="hero__dot"           data-slide="3"  aria-label="Slide 4"></button>
    <button class="hero__dot"           data-slide="4"  aria-label="Slide 5"></button>
    <button class="hero__dot"           data-slide="5"  aria-label="Slide 6"></button>
    <button class="hero__dot"           data-slide="6"  aria-label="Slide 7"></button>
    <button class="hero__dot"           data-slide="7"  aria-label="Slide 8"></button>
    <button class="hero__dot"           data-slide="8"  aria-label="Slide 9"></button>
    <button class="hero__dot"           data-slide="9"  aria-label="Slide 10"></button>
    <button class="hero__dot"           data-slide="10" aria-label="Slide 11"></button>
    <button class="hero__dot"           data-slide="11" aria-label="Slide 12"></button>
  </div>
</section>

<section class="section" id="categories">
  <div class="container">
    <header class="section__head">
      <p class="section__eyebrow"><?= e(t('browse_eyebrow')) ?></p>
      <h2 class="section__title"><?= e(t('shop_by_category')) ?></h2>
      <p class="section__subtitle"><?= e(t('categories_sub')) ?></p>
    </header>
    <div class="categories-grid">
      <?php foreach ($categories as $slug => $c): ?>
        <a href="category.php?slug=<?= e($slug) ?>" class="cat-card">
          <?php if (!empty($c['img'])): ?>
            <img src="<?= e($c['img']) ?>"
                 alt="<?= e($c['label']) ?>"
                 class="cat-card__img"
                 loading="lazy" />
          <?php else: ?>
            <span class="cat-card__icon"><?= $c['icon'] ?></span>
          <?php endif; ?>
          <h3><?= e(t(str_replace('-', '_', $slug))) ?></h3>
          <p><?= e($c['desc']) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--finder">
  <div class="container">
    <div class="finder">
      <header class="finder__head">
        <p class="section__eyebrow"><?= e(t('personalised')) ?></p>
        <h2><?= e(t('beauty_finder')) ?></h2>
        <p><?= e(t('beauty_finder_sub')) ?></p>
      </header>

      <form action="category.php" method="get">
        <div class="finder__step">
          <h3><?= e(t('skin_type_q')) ?></h3>
          <div class="finder__options" data-group="skin">
            <?php foreach (['Dry','Oily','Combination','Sensitive','Normal'] as $tp): ?>
              <button type="button" class="finder-btn" data-value="<?= strtolower($tp) ?>"><?= $tp ?></button>
            <?php endforeach; ?>
          </div>
          <input type="hidden" name="skin" id="finderSkin" />
        </div>
        <div class="finder__step">
          <h3><?= e(t('looking_for_q')) ?></h3>
          <div class="finder__options" data-group="concern">
            <?php foreach (['Hydration','Glow','Even Tone','Acne Care','Sun Protection'] as $c): ?>
              <button type="button" class="finder-btn" data-value="<?= strtolower(str_replace(' ','-',$c)) ?>"><?= $c ?></button>
            <?php endforeach; ?>
          </div>
          <input type="hidden" name="concern" id="finderConcern" />
        </div>
        <button class="btn btn--primary finder__submit" type="submit"><?= e(t('find_products')) ?></button>
      </form>
    </div>
  </div>
</section>

<section class="section" id="body">
  <div class="container">
    <header class="section__head">
      <p class="section__eyebrow"><?= e(t('most_loved')) ?></p>
      <h2 class="section__title"><?= e(t('trending_now')) ?></h2>
      <p class="section__subtitle"><?= e(t('trending_sub')) ?></p>
    </header>
    <div class="products-grid">
      <?php foreach ($trending as $p): ?>
        <article class="product-card">
          <a href="product.php?id=<?= e($p['id']) ?>" class="product-card__media">
            <?php if (!empty($p['image_url'])): ?>
              <img src="<?= e($p['image_url']) ?>" alt="<?= e($p['name']) ?>" class="product-card__img" loading="lazy" />
            <?php else: ?>
              <span aria-hidden="true"><?= $p['emoji'] ?></span>
            <?php endif; ?>
            <span class="product-card__discount"><?= e($p['discount']) ?></span>
          </a>
          <div class="product-card__body">
            <span class="product-card__brand"><?= e($p['brand']) ?></span>
            <h3 class="product-card__name"><?= e($p['name']) ?></h3>
            <div class="product-card__rating"><span>★</span><?= e($p['rating']) ?></div>
            <div class="product-card__price">
              <span class="now"><?= money($p['price']) ?></span>
              <span class="was"><?= money($p['old']) ?></span>
            </div>
            <form action="cart.php" method="post">
              <input type="hidden" name="action" value="add" />
              <input type="hidden" name="id" value="<?= e($p['id']) ?>" />
              <input type="hidden" name="qty" value="1" />
              <button class="product-card__add" type="submit"><?= e(t('add_to_cart')) ?></button>
            </form>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section--deals" id="deals">
  <div class="container">
    <header class="section__head">
      <p class="section__eyebrow"><?= e(t('limited_time')) ?></p>
      <h2 class="section__title"><?= e(t('deals_title')) ?></h2>
      <p class="section__subtitle"><?= e(t('deals_sub')) ?></p>
    </header>
    <div class="deals-grid">
      <article class="deal-card"><span class="deal-card__tag">Body Care</span><h3>Buy 2, Save 10%</h3><p>Mix and match any body lotions.</p></article>
      <article class="deal-card"><span class="deal-card__tag">Fragrance</span><h3>Perfume + Mist</h3><p>Bundle and save.</p></article>
      <article class="deal-card"><span class="deal-card__tag">Makeup</span><h3>Lip Duo</h3><p>Lipstick + liner at a special price.</p></article>
      <article class="deal-card deal-card--flash"><span class="deal-card__tag">⚡ Flash Sale</span><h3>Ends In</h3><div class="countdown" id="countdown">02 : 15 : 43</div></article>
    </div>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="trust-grid">
      <div class="trust-item">
        <img src="https://images.openai.com/static-rsc-4/JH9tp4o7hN6GtYpN0FTx83MnsewzSbx7fCuc5r3osWNLgdzZHSaLqLI-kLkp1kWccslAWqgSfOys4-9mhyAeQeXM2m93YE0vcS4P1xtrO3ggje0AHUgbcCFG0Fj4bDY6EsDTNOCgB0iKd0slgJVIR04ydrrCznRrKzmJygZRUqX2h7Z1hfymV1WLmdjjwp-D?purpose=fullsize"
             alt="Fast Delivery"
             class="trust-item__img"
             loading="lazy" />
        <h4><?= e(t('trust_delivery')) ?></h4>
        <p><?= e(t('trust_delivery_sub')) ?></p>
      </div>
      <div class="trust-item">
        <img src="https://images.unsplash.com/photo-1563013544-824ae1b704d3?auto=format&fit=crop&w=200&q=80"
             alt="Secure Payment"
             class="trust-item__img"
             loading="lazy" />
        <h4><?= e(t('trust_payment')) ?></h4>
        <p><?= e(t('trust_payment_sub')) ?></p>
      </div>
      <div class="trust-item">
        <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=200&q=80"
             alt="Easy Returns"
             class="trust-item__img"
             loading="lazy" />
        <h4><?= e(t('trust_returns')) ?></h4>
        <p><?= e(t('trust_returns_sub')) ?></p>
      </div>
      <div class="trust-item">
        <img src="https://images.openai.com/static-rsc-4/Ndwx8JKZ9e3wfTFsdkYbuKk-pegY-cwppZ1gzl9o4z_7VJGdy7gdyeE17UNd9pqxK5poUYHoS6DfLnKG-0sbq6IJkpdVbb5aV35c8CtbB5tI_KG5ZG9isjgVIHXUIrrTdIry1PQdxtXhdjODbAV5DaeipdpaCb1BK6I2BKqXjiE?purpose=inline"
             alt="Authentic Products"
             class="trust-item__img"
             loading="lazy" />
        <h4><?= e(t('trust_authentic')) ?></h4>
        <p><?= e(t('trust_authentic_sub')) ?></p>
      </div>
    </div>
  </div>
</section>

<!-- =========================================================
     Trusted by leading organisations — scrolling marquee
     ========================================================= -->
<section class="section section--partners">
  <div class="container">
    <header class="section__head">
      <p class="section__eyebrow">Our Partners</p>
      <h2 class="section__title">Trusted by leading organisations</h2>
      <p class="section__subtitle">We collaborate with brands and institutions to deliver world-class cosmetics across Kenya.</p>
    </header>
  </div>

  <div class="partners-marquee">
    <div class="partners-marquee__track">

      <!-- First set -->
      <div class="partner-logo"><img src="https://images.openai.com/static-rsc-4/GjKHvvdnQBQ4CMcUtP4pL9Nm3EILNAXiECZ6n8Vl96FBT_i30X2TK_ICwasSl7z9jl-fhnZ2PVh7UpCO30rztlvnAEMs-8TIvLLVhQYPXGa-fVA7Rv_QuUaP0fAmXHAUUznmX5taGZDskkmZU_3LLER5XMdSC3m-Y4bunciQPTLHXNFAnaaIWDLgm4lZCq5z?purpose=fullsize" alt="NIVEA"></div>
      <div class="partner-logo"><img src="https://upload.wikimedia.org/wikipedia/commons/9/9d/L%27Or%C3%A9al_logo.svg" alt="L'Oréal"></div>
      <div class="partner-logo"><img src="https://images.openai.com/static-rsc-4/OnsZxnl8mCvORH7XJwFG4pQmgi-nkgypVDsvA4ar8tGrUTmgQ5GaQ6llK9qx6fVWmZCqWy4ovY-NteBSOozhd8ZVaCVh5sq1Q0q78FCchZJV3m45DxsYrFP5aHNDbwc3lZuIva_8oLSF8oq3m9hCGQzWeM8F6r9NrONMdaD3pCQ?purpose=inline" alt="Dove"></div>
      <div class="partner-logo"><img src="https://images.openai.com/static-rsc-4/eV3Bdh1fpzosdju0on7yz4CKZc_uQxTsq4kDQfxZHl42eRdfUe7blXO39W7-KbivwfRkm5lNLGHflaKlaRt4L_0Vme7LJNBzF7FJv4xG6ylt7uuSUOdsJ2bPuY7iBPnufpbwy1X4Aiw82mREFkeyqcQtEvJa-j427Kfz2YENj0SgwSc4qsSJ97Ntj2Gpu78k?purpose=fullsize" alt="Maybelline"></div>
      <div class="partner-logo"><img src="https://static.cdnlogo.com/logos/g/48/garnier.svg" alt="Garnier"></div>
      <div class="partner-logo"><img src="https://upload.wikimedia.org/wikipedia/commons/4/42/CeraVe_logo.png" alt="CeraVe"></div>
      <div class="partner-logo"><img src="https://images.openai.com/static-rsc-4/nDMV1T8U3SwDIl5G0HidgYgIgiV8r02OAyBVY9Fd8aB0ATOoF1DRlqMMwSdfM-5pTyTraqN9squu74zopSB7ywHCKeo8p44AJt0BcfdCcxocvFZdsW4pFS1pbyNCPXnBUL3JAvmQzKXSY0bDVo-uN1S335hV4ff3awFOaXtLjgc?purpose=inline" alt="NYX"></div>
      <div class="partner-logo"><img src="https://upload.wikimedia.org/wikipedia/commons/6/6c/Vaseline_new_logo.png" alt="Vaseline"></div>

      <!-- Duplicate set (required for seamless infinite loop) -->
      <div class="partner-logo"><img src="https://images.openai.com/static-rsc-4/GjKHvvdnQBQ4CMcUtP4pL9Nm3EILNAXiECZ6n8Vl96FBT_i30X2TK_ICwasSl7z9jl-fhnZ2PVh7UpCO30rztlvnAEMs-8TIvLLVhQYPXGa-fVA7Rv_QuUaP0fAmXHAUUznmX5taGZDskkmZU_3LLER5XMdSC3m-Y4bunciQPTLHXNFAnaaIWDLgm4lZCq5z?purpose=fullsize" alt="NIVEA"></div>
      <div class="partner-logo"><img src="https://upload.wikimedia.org/wikipedia/commons/9/9d/L%27Or%C3%A9al_logo.svg" alt="L'Oréal"></div>
      <div class="partner-logo"><img src="https://images.openai.com/static-rsc-4/OnsZxnl8mCvORH7XJwFG4pQmgi-nkgypVDsvA4ar8tGrUTmgQ5GaQ6llK9qx6fVWmZCqWy4ovY-NteBSOozhd8ZVaCVh5sq1Q0q78FCchZJV3m45DxsYrFP5aHNDbwc3lZuIva_8oLSF8oq3m9hCGQzWeM8F6r9NrONMdaD3pCQ?purpose=inline" alt="Dove"></div>
      <div class="partner-logo"><img src="https://images.openai.com/static-rsc-4/eV3Bdh1fpzosdju0on7yz4CKZc_uQxTsq4kDQfxZHl42eRdfUe7blXO39W7-KbivwfRkm5lNLGHflaKlaRt4L_0Vme7LJNBzF7FJv4xG6ylt7uuSUOdsJ2bPuY7iBPnufpbwy1X4Aiw82mREFkeyqcQtEvJa-j427Kfz2YENj0SgwSc4qsSJ97Ntj2Gpu78k?purpose=fullsize" alt="Maybelline"></div>
      <div class="partner-logo"><img src="https://static.cdnlogo.com/logos/g/48/garnier.svg" alt="Garnier"></div>
      <div class="partner-logo"><img src="https://upload.wikimedia.org/wikipedia/commons/4/42/CeraVe_logo.png" alt="CeraVe"></div>
      <div class="partner-logo"><img src="https://images.openai.com/static-rsc-4/nDMV1T8U3SwDIl5G0HidgYgIgiV8r02OAyBVY9Fd8aB0ATOoF1DRlqMMwSdfM-5pTyTraqN9squu74zopSB7ywHCKeo8p44AJt0BcfdCcxocvFZdsW4pFS1pbyNCPXnBUL3JAvmQzKXSY0bDVo-uN1S335hV4ff3awFOaXtLjgc?purpose=inline" alt="NYX"></div>
      <div class="partner-logo"><img src="https://upload.wikimedia.org/wikipedia/commons/6/6c/Vaseline_new_logo.png" alt="Vaseline"></div>

    </div>
  </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>