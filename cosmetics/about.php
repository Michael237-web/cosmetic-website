<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

current_lang();

$page_title = t('about_title') . ' — ' . SITE_NAME;
include __DIR__ . '/header.php';

/* Values — each has a real image URL. */
$values = [
    [
        'img'   => 'https://images.unsplash.com/photo-1556228578-8c89e6adf883?auto=format&fit=crop&w=600&q=80',
        'title' => t('about_value_1'),
        'desc'  => t('about_value_1_p'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80',
        'title' => t('about_value_2'),
        'desc'  => t('about_value_2_p'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=600&q=80',
        'title' => t('about_value_3'),
        'desc'  => t('about_value_3_p'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=600&q=80',
        'title' => t('about_value_4'),
        'desc'  => t('about_value_4_p'),
    ],
];

/* Team members */
$team = [
    ['name' => 'Vas K',    'role' => t('about_team_1_role'), 'img' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=600&q=80'],
    ['name' => 'Amina H.', 'role' => t('about_team_2_role'), 'img' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?auto=format&fit=crop&w=600&q=80'],
    ['name' => 'David M.', 'role' => t('about_team_3_role'), 'img' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=600&q=80'],
    ['name' => 'Faith N.', 'role' => t('about_team_4_role'), 'img' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=600&q=80'],
];

/* Milestones */
$milestones = [
    ['year' => '2019', 'title' => t('about_milestone_1_title'), 'desc' => t('about_milestone_1_desc')],
    ['year' => '2021', 'title' => t('about_milestone_2_title'), 'desc' => t('about_milestone_2_desc')],
    ['year' => '2023', 'title' => t('about_milestone_3_title'), 'desc' => t('about_milestone_3_desc')],
    ['year' => '2024', 'title' => t('about_milestone_4_title'), 'desc' => t('about_milestone_4_desc')],
];

/* Why choose us */
$whyUs = [
    ['title' => t('about_why_1_title'), 'desc' => t('about_why_1_desc')],
    ['title' => t('about_why_2_title'), 'desc' => t('about_why_2_desc')],
    ['title' => t('about_why_3_title'), 'desc' => t('about_why_3_desc')],
    ['title' => t('about_why_4_title'), 'desc' => t('about_why_4_desc')],
    ['title' => t('about_why_5_title'), 'desc' => t('about_why_5_desc')],
    ['title' => t('about_why_6_title'), 'desc' => t('about_why_6_desc')],
];
?>

<!-- =========================================================
     HERO with background slideshow
     ========================================================= -->
<section class="about-hero" id="aboutHero">
  <div class="about-hero__slides" aria-hidden="true">
    <div class="about-hero__slide is-active"
         style="background-image: url('https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=1920&q=85');"></div>
    <div class="about-hero__slide"
         style="background-image: url('https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1920&q=85');"></div>
    <div class="about-hero__slide"
         style="background-image: url('https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=1920&q=85');"></div>
    <div class="about-hero__slide"
         style="background-image: url('https://images.unsplash.com/photo-1556228578-8c89e6adf883?auto=format&fit=crop&w=1920&q=85');"></div>
    <div class="about-hero__slide"
         style="background-image: url('https://images.unsplash.com/photo-1571781926291-c477ebfd024b?auto=format&fit=crop&w=1920&q=85');"></div>
  </div>

  <div class="container about-hero__inner">
    <p class="about-hero__eyebrow"><?= e(t('about_story')) ?></p>
    <h1 class="about-hero__title"><?= e(t('about_h1')) ?></h1>
    <p class="about-hero__sub">
      <?= e(t('about_hero_sub')) ?>
    </p>
  </div>

  <div class="about-hero__dots" role="tablist" aria-label="Slideshow navigation">
    <button class="about-hero__dot is-active" data-slide="0" aria-label="Slide 1"></button>
    <button class="about-hero__dot"           data-slide="1" aria-label="Slide 2"></button>
    <button class="about-hero__dot"           data-slide="2" aria-label="Slide 3"></button>
    <button class="about-hero__dot"           data-slide="3" aria-label="Slide 4"></button>
    <button class="about-hero__dot"           data-slide="4" aria-label="Slide 5"></button>
  </div>
</section>

<!-- =========================================================
     STORY
     ========================================================= -->
<section class="section">
  <div class="container" style="max-width:900px;">
    <p style="font-size:16px;line-height:1.9;color:#2d2d2d;margin-bottom:20px;">
      <?= e(t('about_p1')) ?>
    </p>
    <p style="font-size:16px;line-height:1.9;color:#2d2d2d;margin-bottom:40px;">
      <?= e(t('about_p2')) ?>
    </p>
  </div>
</section>

<!-- =========================================================
     MISSION & VISION
     ========================================================= -->
<section class="section" style="padding-top:0;">
  <div class="container" style="max-width:1100px;">
    <div class="about-mv">

      <div class="about-mv__card about-mv__card--mission">
        <div class="about-mv__media">
          <img src="https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=600&q=80"
               alt="<?= e(t('about_mission')) ?>" loading="lazy" />
        </div>
        <div class="about-mv__body">
          <h3><?= e(t('about_mission')) ?></h3>
          <p><?= e(t('about_mission_p')) ?></p>
        </div>
      </div>

      <div class="about-mv__card about-mv__card--vision">
        <div class="about-mv__media">
          <img src="https://images.unsplash.com/photo-1522338242992-e1a54906a8da?auto=format&fit=crop&w=600&q=80"
               alt="<?= e(t('about_vision')) ?>" loading="lazy" />
        </div>
        <div class="about-mv__body">
          <h3><?= e(t('about_vision')) ?></h3>
          <p><?= e(t('about_vision_p')) ?></p>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- =========================================================
     VALUES
     ========================================================= -->
<section class="section" style="padding-top:0;">
  <div class="container" style="max-width:1100px;">
    <header class="section__head">
      <p class="section__eyebrow"><?= e(t('about_what_we_stand')) ?></p>
      <h2 class="section__title"><?= e(t('about_values')) ?></h2>
    </header>

    <div class="about-values">
      <?php foreach ($values as $v): ?>
        <article class="about-value">
          <div class="about-value__media">
            <img src="<?= e($v['img']) ?>" alt="<?= e($v['title']) ?>" loading="lazy" />
          </div>
          <div class="about-value__body">
            <h4><?= e($v['title']) ?></h4>
            <p><?= e($v['desc']) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- =========================================================
     MILESTONES TIMELINE
     ========================================================= -->
<section class="section" style="padding-top:0;">
  <div class="container" style="max-width:1000px;">
    <header class="section__head">
      <p class="section__eyebrow"><?= e(t('about_our_journey')) ?></p>
      <h2 class="section__title"><?= e(t('about_milestones')) ?></h2>
      <p class="section__subtitle"><?= e(t('about_milestones_sub')) ?></p>
    </header>

    <ol class="about-timeline">
      <?php foreach ($milestones as $m): ?>
        <li class="about-timeline__item">
          <span class="about-timeline__year"><?= e($m['year']) ?></span>
          <div class="about-timeline__content">
            <h4><?= e($m['title']) ?></h4>
            <p><?= e($m['desc']) ?></p>
          </div>
        </li>
      <?php endforeach; ?>
    </ol>
  </div>
</section>

<!-- =========================================================
     WHY CHOOSE US
     ========================================================= -->
<section class="section section--why" style="padding-top:0;">
  <div class="container" style="max-width:1100px;">
    <header class="section__head">
      <p class="section__eyebrow"><?= e(t('about_why_eyebrow')) ?></p>
      <h2 class="section__title"><?= e(t('about_why_title')) ?></h2>
    </header>

    <div class="about-why">
      <?php foreach ($whyUs as $w): ?>
        <div class="about-why__item">
          <div class="about-why__icon">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
              <polyline points="20 6 9 17 4 12"/>
            </svg>
          </div>
          <div>
            <h4><?= e($w['title']) ?></h4>
            <p><?= e($w['desc']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- =========================================================
     TEAM
     ========================================================= -->
<section class="section" style="padding-top:0;">
  <div class="container" style="max-width:1100px;">
    <header class="section__head">
      <p class="section__eyebrow"><?= e(t('about_people_eyebrow')) ?></p>
      <h2 class="section__title"><?= e(t('about_team_title')) ?></h2>
      <p class="section__subtitle"><?= e(t('about_team_sub')) ?></p>
    </header>

    <div class="about-team">
      <?php foreach ($team as $p): ?>
        <article class="about-team__member">
          <div class="about-team__media">
            <img src="<?= e($p['img']) ?>" alt="<?= e($p['name']) ?>" loading="lazy" />
          </div>
          <h4><?= e($p['name']) ?></h4>
          <p><?= e($p['role']) ?></p>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- =========================================================
     SITE MAP
     ========================================================= -->
<section class="section" style="padding-top:0;">
  <div class="container" style="max-width:1100px;">
    <div class="sitemap">
      <header class="section__head" style="margin-bottom:0;">
        <p class="section__eyebrow"><?= e(t('sitemap_eyebrow')) ?></p>
        <h2 class="section__title"><?= e(t('sitemap_title')) ?></h2>
        <p class="section__subtitle"><?= e(t('sitemap_sub')) ?></p>
      </header>

      <div class="sitemap__grid">

        <!-- Shop -->
        <div class="sitemap__card">
          <h3 class="sitemap__card-title">
            <span class="sitemap__icon" aria-hidden="true">🛍️</span>
            <?= e(t('sitemap_shop')) ?>
          </h3>
          <ul class="sitemap__list">
            <li><a href="category.php?slug=all"><?= e(t('sitemap_shop_all')) ?></a></li>
            <li><a href="category.php?slug=all"><?= e(t('sitemap_shop_new')) ?></a></li>
            <li><a href="offers.php"><?= e(t('sitemap_shop_offers')) ?></a></li>
            <li><a href="services.php"><?= e(t('sitemap_shop_services')) ?></a></li>
          </ul>
        </div>

        <!-- Account -->
        <div class="sitemap__card">
          <h3 class="sitemap__card-title">
            <span class="sitemap__icon" aria-hidden="true">👤</span>
            <?= e(t('sitemap_account')) ?>
          </h3>
          <ul class="sitemap__list">
            <li><a href="login.php"><?= e(t('sitemap_acc_login')) ?></a></li>
            <li><a href="register.php"><?= e(t('sitemap_acc_register')) ?></a></li>
            <li><a href="cart.php"><?= e(t('sitemap_acc_cart')) ?></a></li>
            <li><a href="checkout.php"><?= e(t('sitemap_acc_checkout')) ?></a></li>
          </ul>
        </div>

        <!-- Company -->
        <div class="sitemap__card">
          <h3 class="sitemap__card-title">
            <span class="sitemap__icon" aria-hidden="true">💼</span>
            <?= e(t('sitemap_company')) ?>
          </h3>
          <ul class="sitemap__list">
            <li><a href="about.php"><?= e(t('sitemap_comp_about')) ?></a></li>
            <li><a href="services.php"><?= e(t('sitemap_comp_services')) ?></a></li>
            <li><a href="contact.php"><?= e(t('sitemap_comp_contact')) ?></a></li>
            <li><a href="#"><?= e(t('sitemap_comp_blog')) ?></a></li>
          </ul>
        </div>

        <!-- Help & Legal -->
        <div class="sitemap__card">
          <h3 class="sitemap__card-title">
            <span class="sitemap__icon" aria-hidden="true">📄</span>
            <?= e(t('sitemap_support')) ?>
          </h3>
          <ul class="sitemap__list">
            <li><a href="services.php#faq"><?= e(t('sitemap_sup_faq')) ?></a></li>
            <li><a href="privacy.php"><?= e(t('sitemap_sup_privacy')) ?></a></li>
            <li><a href="terms.php"><?= e(t('sitemap_sup_terms')) ?></a></li>
            <li><a href="cookies.php"><?= e(t('sitemap_sup_cookies')) ?></a></li>
          </ul>
        </div>

      </div>
    </div>
  </div>
</section>

<!-- =========================================================
     GOOGLE MAP
     ========================================================= -->
<section class="section" style="padding-top:0;">
  <div class="container" style="max-width:1100px;">
    <header class="section__head">
      <p class="section__eyebrow"><?= e(t('map_eyebrow')) ?></p>
      <h2 class="section__title"><?= e(t('map_title')) ?></h2>
      <p class="section__subtitle"><?= e(t('map_sub')) ?></p>
    </header>

    <div class="map-section">

      <!-- Info panel -->
      <div class="map-info">

        <div class="map-info__block">
          <span class="map-info__icon" aria-hidden="true">📍</span>
          <div>
            <h4><?= e(t('map_address_h')) ?></h4>
            <p><?= e(t('map_address')) ?></p>
          </div>
        </div>

        <div class="map-info__block">
          <span class="map-info__icon" aria-hidden="true">🕒</span>
          <div>
            <h4><?= e(t('map_hours_h')) ?></h4>
            <p><?= e(t('map_hours')) ?></p>
          </div>
        </div>

        <div class="map-info__block">
          <span class="map-info__icon" aria-hidden="true">📞</span>
          <div>
            <h4><?= e(t('map_phone_h')) ?></h4>
            <p><a href="tel:+254746674121">+254 746674121</a></p>
          </div>
        </div>

        <div class="map-info__block">
          <span class="map-info__icon" aria-hidden="true">✉️</span>
          <div>
            <h4><?= e(t('map_email_h')) ?></h4>
            <p><a href="mailto:hello@glowbeauty.co.ke">info@glowbeauty.co.ke</a></p>
          </div>
        </div>

        <a class="btn btn--primary"
           href="https://www.google.com/maps/dir/?api=1&destination=Kimathi+Street+Nairobi+Kenya"
           target="_blank" rel="noopener">
          <?= e(t('map_directions')) ?>
        </a>
      </div>

      <!-- Google Map iframe -->
      <div class="map-frame">
        <iframe
          src="https://www.google.com/maps?q=Kimathi%20Street%20Nairobi%20Kenya&output=embed"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          allowfullscreen
          title="<?= e(t('map_title')) ?>">
        </iframe>
      </div>

    </div>
  </div>
</section>

<!-- =========================================================
     CTA
     ========================================================= -->
<section class="section" style="padding-top:0;">
  <div class="container" style="max-width:900px;">
    <div class="about-cta">
      <h3><?= e(t('about_cta')) ?></h3>
      <p><?= e(t('about_browse_collection')) ?></p>
      <a href="category?slug=all" class="btn btn--primary"><?= e(t('shop_now')) ?></a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>