</main>

<footer class="site-footer">

  <!-- ============ NEWSLETTER STRIP (optional) ============ -->
  <div class="footer-newsletter">
    <div class="container footer-newsletter__inner">
      <div class="footer-newsletter__text">
        <h3><?= e(t('offers_subscribe')) ?></h3>
        <p><?= e(t('offers_subscribe_p')) ?></p>
      </div>
      <form class="footer-newsletter__form" action="<?= e(url('offers')) ?>" method="post">
        <input type="hidden" name="action" value="subscribe" />
        <input type="email" name="email" required
               placeholder="<?= e(t('offers_email')) ?>"
               aria-label="<?= e(t('offers_email')) ?>" />
        <button type="submit" class="btn btn--primary"><?= e(t('offers_btn')) ?></button>
      </form>
    </div>
  </div>

  <!-- ============ MAIN FOOTER GRID ============ -->
  <div class="container footer-main">

    <!-- Brand column -->
    <div class="footer-col footer-col--brand">
      <a href="<?= e(url('index')) ?>" class="footer-logo" aria-label="<?= e(SITE_NAME) ?>">
        <img src="<?= e(url('cosmeticlogo.png')) ?>" alt="<?= e(SITE_NAME) ?>" class="site-logo site-logo--footer" />
      </a>
      <p class="footer-tagline"><?= e(t('footer_tagline')) ?></p>

      <ul class="footer-social" aria-label="Social media">
        <li>
          <a href="#" aria-label="Facebook" class="footer-social__link">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
              <path d="M22 12a10 10 0 1 0-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.7-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.2c-1.2 0-1.6.8-1.6 1.6V12h2.7l-.4 2.9h-2.3v7A10 10 0 0 0 22 12z"/>
            </svg>
          </a>
        </li>
        <li>
          <a href="#" aria-label="Instagram" class="footer-social__link">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
              <rect x="3" y="3" width="18" height="18" rx="5"/>
              <circle cx="12" cy="12" r="4"/>
              <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
            </svg>
          </a>
        </li>
        <li>
          <a href="#" aria-label="TikTok" class="footer-social__link">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
              <path d="M16.5 3c.3 1.7 1.4 3 3 3.5v2.5c-1.1 0-2.2-.3-3.2-.9v6.4c0 3.2-2.6 5.8-5.8 5.8S4.7 17.7 4.7 14.5 7.3 8.7 10.5 8.7c.3 0 .6 0 .9.1v2.6c-.3-.1-.6-.2-.9-.2-1.8 0-3.2 1.4-3.2 3.2s1.4 3.2 3.2 3.2 3.2-1.4 3.2-3.2V3h2.8z"/>
            </svg>
          </a>
        </li>
        <li>
          <a href="#" aria-label="X (Twitter)" class="footer-social__link">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
              <path d="M17.5 3h3l-7 8 8 10h-3.5l-5.2-6.5L6.5 21H3.5l7.5-8.6L3 3h3.5l4.7 6.1L17.5 3z"/>
            </svg>
          </a>
        </li>
        <li>
          <a href="#" aria-label="WhatsApp" class="footer-social__link">
            <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true">
              <path d="M20 3.9A10 10 0 0 0 3.9 17.7L3 21.5l3.9-1A10 10 0 1 0 20 3.9zM12 20a8 8 0 0 1-4.1-1.1l-.3-.2-2.3.6.6-2.2-.2-.3A8 8 0 1 1 12 20zm4.4-6c-.2-.1-1.4-.7-1.6-.8s-.4-.1-.5.1-.6.8-.8.9-.3.2-.5.1a6.6 6.6 0 0 1-1.9-1.2 7.3 7.3 0 0 1-1.4-1.7c-.1-.3 0-.4.1-.5l.4-.4.2-.4v-.4l-.8-1.9c-.2-.5-.4-.4-.5-.4h-.5c-.2 0-.5.1-.7.4-.3.3-1 .9-1 2.3s1 2.7 1.2 2.9 2 3.1 5 4.3c2.1.9 2.5.7 2.9.6.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2z"/>
            </svg>
          </a>
        </li>
      </ul>
    </div>

    <!-- Shop links -->
    <div class="footer-col footer-col--links" data-collapsible>
      <h4 class="footer-col__title" data-toggle>
        <?= e(t('shop')) ?>
        <span class="footer-col__chevron" aria-hidden="true"></span>
      </h4>
      <ul class="footer-col__list">
        <li><a href="<?= e(url('category?slug=all')) ?>"><?= e(t('footer_all')) ?></a></li>
        <li><a href="<?= e(url('category?slug=body-care')) ?>"><?= e(t('body_care')) ?></a></li>
        <li><a href="<?= e(url('category?slug=face-care')) ?>"><?= e(t('face_care')) ?></a></li>
        <li><a href="<?= e(url('category?slug=makeup')) ?>"><?= e(t('makeup')) ?></a></li>
        <li><a href="<?= e(url('category?slug=fragrance')) ?>"><?= e(t('fragrance')) ?></a></li>
        <li><a href="<?= e(url('category?slug=nails')) ?>"><?= e(t('nails')) ?></a></li>
      </ul>
    </div>

    <!-- Company links -->
    <div class="footer-col footer-col--links" data-collapsible>
      <h4 class="footer-col__title" data-toggle>
        <?= e(t('footer_company')) ?>
        <span class="footer-col__chevron" aria-hidden="true"></span>
      </h4>
      <ul class="footer-col__list">
        <li><a href="<?= e(url('about')) ?>"><?= e(t('footer_about')) ?></a></li>
        <li><a href="<?= e(url('services')) ?>"><?= e(t('services_title')) ?></a></li>
        <li><a href="<?= e(url('offers')) ?>"><?= e(t('offers_title')) ?></a></li>
        <li><a href="<?= e(url('contact')) ?>"><?= e(t('footer_contact')) ?></a></li>
        <li><a href="<?= e(url('register')) ?>"><?= e(t('register_title')) ?></a></li>
        <li><a href="<?= e(url('checkout')) ?>"><?= e(t('checkout')) ?></a></li>
      </ul>
    </div>

    <!-- Contact / Help -->
    <div class="footer-col footer-col--contact" data-collapsible>
      <h4 class="footer-col__title" data-toggle>
        <?= e(t('footer_contact_h')) ?>
        <span class="footer-col__chevron" aria-hidden="true"></span>
      </h4>
      <ul class="footer-col__list">
        <li>
          <span class="footer-contact__icon" aria-hidden="true">📞</span>
          <a href="tel:+254746674121">+254 746674121</a>
        </li>
        <li>
          <span class="footer-contact__icon" aria-hidden="true">✉️</span>
          <a href="mailto:hello@glowbeauty.co.ke">hello@glowbeauty.co.ke</a>
        </li>
        <li>
          <span class="footer-contact__icon" aria-hidden="true">📍</span>
          <span>Nairobi, Kenya</span>
        </li>
        <li>
          <span class="footer-contact__icon" aria-hidden="true">🕒</span>
          <span>Mon–Sat · 9am – 7pm</span>
        </li>
      </ul>
    </div>
  </div>

  <!-- ============ PAYMENT METHODS ============ -->
  <div class="container footer-payments">
    <span class="footer-payments__label"><?= e(t('payment')) ?></span>
    <ul class="footer-payments__list">
      <li class="footer-payments__item">M-PESA</li>
      <li class="footer-payments__item">VISA</li>
      <li class="footer-payments__item">Mastercard</li>
      <li class="footer-payments__item">PayPal</li>
    </ul>
  </div>

  <!-- ============ BOTTOM BAR ============ -->
  <div class="footer-bottom">
    <div class="container footer-bottom__inner">
      <p>© <?= date('Y') ?> <?= SITE_NAME ?>. <?= e(t('footer_rights')) ?></p>

      <ul class="footer-legal">
        <li><a href="<?= e(url('privacy')) ?>"><?= e(t('privacy_title')) ?></a></li>
        <li><a href="<?= e(url('terms')) ?>"><?= e(t('terms_title')) ?></a></li>
        <li><a href="<?= e(url('cookies')) ?>"><?= e(t('cookies_title')) ?></a></li>
      </ul>

      <a href="#top" class="footer-top-btn" aria-label="Back to top">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <path d="M12 19V5M5 12l7-7 7 7"/>
        </svg>
      </a>
    </div>
  </div>
</footer>

<script src="<?= e(url('script.js')) ?>" defer></script>
<?php if (!empty($extra_js)): foreach ((array)$extra_js as $js): ?>
  <script src="<?= e(url($js)) ?>" defer></script>
<?php endforeach; endif; ?>
</body>
</html>