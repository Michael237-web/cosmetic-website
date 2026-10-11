<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/products.php';

// IMPORTANT: call current_lang() before any output so the
// language redirect (if ?lang=xx is in the URL) can run.
current_lang();

$page_title = t('services_title') . ' — ' . SITE_NAME;
include __DIR__ . '/header.php';

/* Each service has a real image URL and a title + description. */
$services = [
    [
        'img'   => 'https://images.unsplash.com/photo-1596704017254-9b121068fb31?auto=format&fit=crop&w=800&q=80',
        'title' => t('service_1'),
        'desc'  => t('service_1_p'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?auto=format&fit=crop&w=800&q=80',
        'title' => t('service_2'),
        'desc'  => t('service_2_p'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1549465220-1a8b9238cd48?auto=format&fit=crop&w=800&q=80',
        'title' => t('service_3'),
        'desc'  => t('service_3_p'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=800&q=80',
        'title' => t('service_4'),
        'desc'  => t('service_4_p'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=800&q=80',
        'title' => t('service_5'),
        'desc'  => t('service_5_p'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=800&q=80',
        'title' => t('service_6'),
        'desc'  => t('service_6_p'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=800&q=80',
        'title' => t('service_7'),
        'desc'  => t('service_7_p'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1607083206968-13611e3d76db?auto=format&fit=crop&w=800&q=80',
        'title' => t('service_8'),
        'desc'  => t('service_8_p'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=800&q=80',
        'title' => t('service_9'),
        'desc'  => t('service_9_p'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1611746872915-64382b5c76da?auto=format&fit=crop&w=800&q=80',
        'title' => t('service_10'),
        'desc'  => t('service_10_p'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1607082349566-187342175e2f?auto=format&fit=crop&w=800&q=80',
        'title' => t('service_11'),
        'desc'  => t('service_11_p'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1513201099705-a9746e1e201f?auto=format&fit=crop&w=800&q=80',
        'title' => t('service_12'),
        'desc'  => t('service_12_p'),
    ],
];

/* ---------- FAQ — most common customer questions ---------- */
$faqs = [
    ['q' => t('faq_q1'),  'a' => t('faq_a1')],
    ['q' => t('faq_q2'),  'a' => t('faq_a2')],
    ['q' => t('faq_q3'),  'a' => t('faq_a3')],
    ['q' => t('faq_q4'),  'a' => t('faq_a4')],
    ['q' => t('faq_q5'),  'a' => t('faq_a5')],
    ['q' => t('faq_q6'),  'a' => t('faq_a6')],
    ['q' => t('faq_q7'),  'a' => t('faq_a7')],
    ['q' => t('faq_q8'),  'a' => t('faq_a8')],
    ['q' => t('faq_q9'),  'a' => t('faq_a9')],
    ['q' => t('faq_q10'), 'a' => t('faq_a10')],
];
?>

<section class="section">
  <div class="container" style="max-width:1100px;">
    <header class="section__head">
      <p class="section__eyebrow"><?= e(t('services_eyebrow')) ?></p>
      <h2 class="section__title"><?= e(t('services_title')) ?></h2>
      <p class="section__subtitle"><?= e(t('services_sub')) ?></p>
    </header>

    <div class="services-grid">
      <?php foreach ($services as $s): ?>
        <article class="service-card">
          <div class="service-card__media">
            <img src="<?= e($s['img']) ?>"
                 alt="<?= e($s['title']) ?>"
                 class="service-card__img"
                 loading="lazy" />
          </div>
          <div class="service-card__body">
            <h3 class="service-card__title"><?= e($s['title']) ?></h3>
            <p class="service-card__desc"><?= e($s['desc']) ?></p>
          </div>
        </article>
      <?php endforeach; ?>
    </div>

    <!-- =============================================
         FAQ SECTION
         ============================================= -->
    <section class="faq">
      <header class="section__head">
        <p class="section__eyebrow"><?= e(t('faq_eyebrow')) ?></p>
        <h2 class="section__title"><?= e(t('faq_title')) ?></h2>
        <p class="section__subtitle"><?= e(t('faq_sub')) ?></p>
      </header>

      <div class="faq__list">
        <?php foreach ($faqs as $i => $f): ?>
          <details class="faq__item" <?= $i === 0 ? 'open' : '' ?>>
            <summary class="faq__question">
              <span><?= e($f['q']) ?></span>
              <span class="faq__icon" aria-hidden="true">+</span>
            </summary>
            <div class="faq__answer">
              <p><?= e($f['a']) ?></p>
            </div>
          </details>
        <?php endforeach; ?>
      </div>
    </section>

    <div class="services-cta">
      <h3 class="services-cta__title"><?= e(t('services_cta')) ?></h3>
      <a href="contact" class="btn btn--primary"><?= e(t('footer_contact')) ?></a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>