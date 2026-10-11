<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

current_lang();

$page_title = t('terms_title') . ' — ' . SITE_NAME;
$auth_page  = false;
$extra_css  = ['legal.css'];

$toc = [
    's1'  => t('terms_s1_h'),
    's2'  => t('terms_s2_h'),
    's3'  => t('terms_s3_h'),
    's4'  => t('terms_s4_h'),
    's5'  => t('terms_s5_h'),
    's6'  => t('terms_s6_h'),
    's7'  => t('terms_s7_h'),
    's8'  => t('terms_s8_h'),
    's9'  => t('terms_s9_h'),
    's10' => t('terms_s10_h'),
];

include __DIR__ . '/header.php';
?>

<section class="legal-hero">
  <div class="container">
    <h1 class="legal-hero__title"><?= e(t('terms_title')) ?></h1>
    <p class="legal-hero__sub"><?= e(t('terms_sub')) ?></p>
    <span class="legal-hero__updated">
      <?= e(t('legal_last_updated')) ?>: <strong><?= e(t('terms_updated')) ?></strong>
    </span>
  </div>
</section>

<div class="container">
  <div class="legal-layout">

    <aside class="legal-toc" aria-label="<?= e(t('legal_toc')) ?>">
      <h2 class="legal-toc__title"><?= e(t('legal_toc')) ?></h2>
      <ul class="legal-toc__list">
        <?php foreach ($toc as $id => $label): ?>
          <li><a href="#<?= e($id) ?>"><?= e($label) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </aside>

    <article class="legal-body">

      <h2 id="s1"><?= e(t('terms_s1_h')) ?></h2>
      <p><?= e(t('terms_s1_p')) ?></p>

      <h2 id="s2"><?= e(t('terms_s2_h')) ?></h2>
      <p><?= e(t('terms_s2_p')) ?></p>

      <h2 id="s3"><?= e(t('terms_s3_h')) ?></h2>
      <p><?= e(t('terms_s3_p')) ?></p>

      <h2 id="s4"><?= e(t('terms_s4_h')) ?></h2>
      <p><?= e(t('terms_s4_p')) ?></p>
      <ul>
        <li><?= e(t('terms_s4_b1')) ?></li>
        <li><?= e(t('terms_s4_b2')) ?></li>
        <li><?= e(t('terms_s4_b3')) ?></li>
      </ul>

      <h2 id="s5"><?= e(t('terms_s5_h')) ?></h2>
      <p><?= e(t('terms_s5_p')) ?></p>

      <h2 id="s6"><?= e(t('terms_s6_h')) ?></h2>
      <p><?= e(t('terms_s6_p')) ?></p>

      <h2 id="s7"><?= e(t('terms_s7_h')) ?></h2>
      <p><?= e(t('terms_s7_p')) ?></p>

      <h2 id="s8"><?= e(t('terms_s8_h')) ?></h2>
      <p><?= e(t('terms_s8_p')) ?></p>

      <h2 id="s9"><?= e(t('terms_s9_h')) ?></h2>
      <p><?= e(t('terms_s9_p')) ?></p>

      <h2 id="s10"><?= e(t('terms_s10_h')) ?></h2>
      <p><?= e(t('terms_s10_p')) ?></p>

      <div class="legal-cta">
        <h3><?= e(t('legal_contact_cta')) ?></h3>
        <a href="contact.php" class="btn btn--primary"><?= e(t('legal_contact_btn')) ?></a>
      </div>

      <a href="#" class="legal-back">↑ <?= e(t('legal_back_top')) ?></a>

    </article>
  </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>