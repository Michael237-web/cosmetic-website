<?php
require_once __DIR__ . '/config.php';
$page_title = $page_title ?? SITE_NAME . ' — Premium Cosmetics in Kenya';
$current    = basename($_SERVER['PHP_SELF']);
$current    = preg_replace('/\.php$/i', '', $current); // pretty-URL friendly
$lang       = current_lang();
$langs      = available_langs();
$current_path = current_pretty_url(); // e.g. "/glowbeauty/about"
?>
<!DOCTYPE html>
<html lang="<?= e($lang) ?>" dir="<?= is_rtl() ? 'rtl' : 'ltr' ?>">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="<?= e(t('footer_tagline')) ?>" />
  <title><?= e($page_title) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= e(url('styles.css')) ?>" />
  <?php if (!empty($auth_page)): ?>
    <link rel="stylesheet" href="<?= e(url('auth.css')) ?>" />
  <?php endif; ?>
  <?php if (!empty($extra_css)): foreach ((array)$extra_css as $css): ?>
    <link rel="stylesheet" href="<?= e(url($css)) ?>" />
  <?php endforeach; endif; ?>
  <link rel="icon" type="image/png" href="cosmeticlogo.png"/>
</head>
<body>

<?php
/* ✅ GLOBAL FLASH MESSAGE DISPLAY */
$global_success = flash('success');
$global_error   = flash('error');
$global_info    = flash('info');
?>
<?php if ($global_success || $global_error || $global_info): ?>
  <div class="flash-container" id="flashContainer">
    <?php if ($global_success): ?>
      <div class="flash flash--success" data-flash>
        <span class="flash__icon">✅</span>
        <span class="flash__text"><?= e($global_success) ?></span>
        <button class="flash__close" aria-label="Close">×</button>
      </div>
    <?php endif; ?>

    <?php if ($global_error): ?>
      <div class="flash flash--error" data-flash>
        <span class="flash__icon">⚠️</span>
        <span class="flash__text"><?= e($global_error) ?></span>
        <button class="flash__close" aria-label="Close">×</button>
      </div>
    <?php endif; ?>

    <?php if ($global_info): ?>
      <div class="flash flash--info" data-flash>
        <span class="flash__icon">ℹ️</span>
        <span class="flash__text"><?= e($global_info) ?></span>
        <button class="flash__close" aria-label="Close">×</button>
      </div>
    <?php endif; ?>
  </div>
<?php endif; ?>

<header class="site-header" id="siteHeader">
  <div class="top-bar">
    <div class="container top-bar__inner">
      <span class="top-bar__item"> <?= e(t('free_delivery')) ?></span>
      <span class="top-bar__divider">•</span>
      <span class="top-bar__item">💳 <?= e(t('mpesa_card')) ?></span>
    </div>
  </div>

  <div class="navbar container">

    <a href="<?= e(url('index')) ?>" class="logo" aria-label="<?= e(SITE_NAME) ?>">
      <img src="<?= e(url('cosmeticlogo.png')) ?>" alt="<?= e(SITE_NAME) ?>" class="site-logo" />
    </a>

    <nav class="main-nav" aria-label="Primary">
      <ul>
        <li><a href="<?= e(url('index')) ?>" <?= $current === 'index' ? 'class="active"' : '' ?>><?= e(t('home')) ?></a></li>
        <li><a href="<?= e(url('about')) ?>" <?= $current === 'about' ? 'class="active"' : '' ?>><?= e(t('footer_about')) ?></a></li>
        <li><a href="<?= e(url('category?slug=all')) ?>" <?= ($current === 'category' && ($_GET['slug'] ?? '') === 'all') ? 'class="active"' : '' ?>><?= e(t('shop')) ?></a></li>
        <li class="has-dropdown">
          <a href="<?= e(url('category?slug=all')) ?>"><?= e(t('shop_by_category')) ?> ▾</a>
          <ul class="dropdown">
            <li><a href="<?= e(url('category?slug=body-care')) ?>"> <?= e(t('body_care')) ?></a></li>
            <li><a href="<?= e(url('category?slug=face-care')) ?>"> <?= e(t('face_care')) ?></a></li>
            <li><a href="<?= e(url('category?slug=makeup')) ?>"><?= e(t('makeup')) ?></a></li>
            <li><a href="<?= e(url('category?slug=hair-care')) ?>"> <?= e(t('hair_care')) ?></a></li>
            <li><a href="<?= e(url('category?slug=fragrance')) ?>"> <?= e(t('fragrance')) ?></a></li>
            <li><a href="<?= e(url('category?slug=nails')) ?>"> <?= e(t('nails')) ?></a></li>
          </ul>
        </li>
        <li><a href="<?= e(url('services')) ?>" <?= $current === 'services' ? 'class="active"' : '' ?>><?= e(t('services_title')) ?></a></li>
        <li><a href="<?= e(url('offers')) ?>" <?= $current === 'offers' ? 'class="active nav-highlight"' : 'class="nav-highlight"' ?>><?= e(t('offers')) ?></a></li>
      </ul>
    </nav>

    <form class="search-bar" action="<?= e(url('category')) ?>" method="get" role="search">
      <svg class="search-bar__icon" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
        <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
      </svg>
      <label for="searchInput" class="visually-hidden"><?= e(t('search_placeholder')) ?></label>
      <input id="searchInput" type="search" name="q"
             placeholder="<?= e(t('search_placeholder')) ?>"
             value="<?= e($_GET['q'] ?? '') ?>" autocomplete="off" />
    </form>

    <div class="header-icons">
      <div class="lang-switcher" id="langSwitcher">
        <button class="lang-switcher__btn" type="button" aria-haspopup="listbox" aria-expanded="false">
          <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
            <circle cx="12" cy="12" r="10"/>
            <line x1="2" y1="12" x2="22" y2="12"/>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
          </svg>
          <span class="lang-switcher__current"><?= e($langs[$lang]['label']) ?></span>
        </button>
        <ul class="lang-switcher__menu" role="listbox">
          <?php foreach ($langs as $code => $meta): ?>
            <?php
              $qs = $_GET;
              unset($qs['lang']);
              $qs['lang'] = $code;
              $href = $current_path . '?' . http_build_query($qs);
            ?>
            <li role="option" aria-selected="<?= $code === $lang ? 'true' : 'false' ?>">
              <a href="<?= e($href) ?>"
                 class="lang-switcher__link <?= $code === $lang ? 'is-active' : '' ?>"
                 data-lang="<?= e($code) ?>">
                <span class="lang-switcher__flag"><?= $meta['flag'] ?></span>
                <span><?= e($meta['name']) ?></span>
              </a>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>

      <a class="icon-btn" href="#" aria-label="<?= e(t('wishlist')) ?>">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 1 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
      </a>

      <a class="icon-btn" href="<?= e(url('cart')) ?>" aria-label="<?= e(t('cart')) ?>">
        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
        <span class="badge" id="cartCount"><?= cart_count() ?></span>
      </a>

      <?php if (is_logged_in()): ?>
        <!-- ✅ Logged in: User dropdown menu with Logout -->
        <div class="user-menu" id="userMenu">
          <button class="icon-btn user-menu__btn" type="button" aria-haspopup="menu" aria-expanded="false" aria-label="<?= e(t('account')) ?>">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
          </button>

          <div class="user-menu__dropdown">
            <div class="user-menu__greeting">
              <span class="user-menu__avatar"><?= e(strtoupper(substr(current_user_name(), 0, 1))) ?></span>
              <div>
                <strong><?= e(current_user_name()) ?></strong>
                <small><?= e(t('account')) ?></small>
              </div>
            </div>
            <ul class="user-menu__list">
              <li><a href="<?= e(url('index')) ?>">🏠 <?= e(t('home')) ?></a></li>
              <li><a href="<?= e(url('cart')) ?>">🛒 <?= e(t('cart')) ?></a></li>
              <li class="user-menu__divider"></li>
              <li>
                <a href="<?= e(url('logout')) ?>" class="user-menu__logout">
                  🚪 <?= e(t('logout') ?? 'Logout') ?>
                </a>
              </li>
            </ul>
          </div>
        </div>
      <?php else: ?>
        <!-- ✅ Not logged in: link to login page -->
        <a class="icon-btn" href="<?= e(url('login')) ?>" aria-label="<?= e(t('account')) ?>">
          <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
          </svg>
        </a>
      <?php endif; ?>
    </div>

    <!-- Hamburger -->
    <button class="mobile-menu-btn" id="mobileMenuBtn" aria-label="Open menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

  </div>
</header>

<div class="overlay" id="mobileOverlay" hidden></div>

<aside class="mobile-nav" id="mobileNav" aria-hidden="true">
  <header class="mobile-nav__head">
    <a href="<?= e(url('index')) ?>" class="logo" aria-label="<?= e(SITE_NAME) ?>">
      <img src="<?= e(url('cosmeticlogo.png')) ?>" alt="<?= e(SITE_NAME) ?>" class="site-logo site-logo--mobile" />
    </a>
    <button class="mobile-nav__close" id="mobileMenuClose" aria-label="Close menu">×</button>
  </header>

  <nav class="mobile-nav__body">
    <ul>
      <li><a href="<?= e(url('index')) ?>"><?= e(t('home')) ?></a></li>
      <li><a href="<?= e(url('about')) ?>"><?= e(t('footer_about')) ?></a></li>
      <li><a href="<?= e(url('category?slug=all')) ?>"><?= e(t('shop')) ?></a></li>
      <li class="mobile-nav__group">
        <span class="mobile-nav__label"><?= e(t('shop_by_category')) ?></span>
        <ul>
          <li><a href="<?= e(url('category?slug=body-care')) ?>">🧴 <?= e(t('body_care')) ?></a></li>
          <li><a href="<?= e(url('category?slug=face-care')) ?>">✨ <?= e(t('face_care')) ?></a></li>
          <li><a href="<?= e(url('category?slug=makeup')) ?>">💄 <?= e(t('makeup')) ?></a></li>
          <li><a href="<?= e(url('category?slug=hair-care')) ?>">💇‍♀️ <?= e(t('hair_care')) ?></a></li>
          <li><a href="<?= e(url('category?slug=fragrance')) ?>">🌸 <?= e(t('fragrance')) ?></a></li>
          <li><a href="<?= e(url('category?slug=nails')) ?>">💅 <?= e(t('nails')) ?></a></li>
        </ul>
      </li>
      <li><a href="<?= e(url('services')) ?>"><?= e(t('services_title')) ?></a></li>
      <li><a href="<?= e(url('offers')) ?>" class="nav-highlight"><?= e(t('offers')) ?></a></li>

      <?php if (is_logged_in()): ?>
        <li class="mobile-nav__group">
          <span class="mobile-nav__label"><?= e(t('account')) ?></span>
          <ul>
            <li><a href="<?= e(url('index')) ?>">🏠 <?= e(t('home')) ?></a></li>
            <li><a href="<?= e(url('cart')) ?>">🛒 <?= e(t('cart')) ?></a></li>
            <li><a href="<?= e(url('logout')) ?>" style="color:#dc2626;">🚪 <?= e(t('logout') ?? 'Logout') ?></a></li>
          </ul>
        </li>
      <?php else: ?>
        <li><a href="<?= e(url('login')) ?>">👤 <?= e(t('login_btn')) ?></a></li>
        <li><a href="<?= e(url('register')) ?>">✨ <?= e(t('register_btn')) ?></a></li>
      <?php endif; ?>
    </ul>
  </nav>

  <footer class="mobile-nav__foot">
    <a href="<?= e(url('cart')) ?>" class="btn btn--primary btn--full"><?= e(t('cart')) ?> · <?= cart_count() ?></a>
  </footer>
</aside>

<main>