<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

/* Force language detection/redirect BEFORE any output */
current_lang();

/* If already logged in, redirect to home */
if (!empty($_SESSION['customer_id'])) {
    redirect('index.php');
}

$page_title = t('register_title') . ' — ' . SITE_NAME;
$auth_page  = true; /* tells header.php to load auth.css */

$error = null;

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $name     = trim($_POST['name']     ?? '');
    $email    = trim($_POST['email']    ?? '');
    $phone    = trim($_POST['phone']    ?? '');
    $county   = trim($_POST['county']   ?? '');
    $password = $_POST['password']      ?? '';
    $confirm  = $_POST['password2']     ?? '';

    if (!$name || !$email || !$phone || !$password) {
        $error = t('register_error');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = t('contact_bad_email');
    } elseif (strlen($password) < 8) {
        $error = t('register_short');
    } elseif ($password !== $confirm) {
        $error = t('register_mismatch');
    } else {
        $pdo = db();
        $exists = $pdo->prepare("SELECT id FROM cosmetics_customers WHERE email = ? LIMIT 1");
        $exists->execute([$email]);

        if ($exists->fetch()) {
            $error = t('register_exists');
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO cosmetics_customers (full_name, email, phone, county, pass_hash)
                VALUES (?,?,?,?,?)
            ");
            $stmt->execute([$name, $email, $phone, $county, $hash]);

            /* ✅ Save flash message and redirect to LOGIN (not index) */
            $_SESSION['flash']['success'] = t('register_welcome');
            redirect('login');
        }
    }
}

/* Slideshow images */
$slides = [
    [
        'img'   => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=1200&q=80',
        'title' => 'Discover Your Glow',
        'text'  => 'Premium beauty essentials, delivered across Kenya.',
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1200&q=80',
        'title' => 'Authentic. Always.',
        'text'  => 'Sourced from authorised distributors only.',
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1200&q=80',
        'title' => 'Skincare, Perfected',
        'text'  => 'Curated routines for every skin type.',
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=1200&q=80',
        'title' => 'Fast. Nationwide.',
        'text'  => 'Same-day in Nairobi, 2–4 days countrywide.',
    ],
];

include __DIR__ . '/header.php';
?>

<section class="auth-body">
  <div class="auth-shell">

    <!-- ================= LEFT: SLIDESHOW ================= -->
    <div class="auth-media">
      <?php foreach ($slides as $i => $s): ?>
        <div class="auth-slide <?= $i === 0 ? 'is-active' : '' ?>"
             style="background-image:url('<?= e($s['img']) ?>');"
             data-index="<?= $i ?>"></div>
      <?php endforeach; ?>

      <div class="auth-media__caption" id="authCaption">
        <h3><?= e($slides[0]['title']) ?></h3>
        <p><?= e($slides[0]['text']) ?></p>
      </div>

      <div class="auth-dots" id="authDots">
        <?php foreach ($slides as $i => $s): ?>
          <span class="<?= $i === 0 ? 'is-active' : '' ?>" data-index="<?= $i ?>"></span>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- ================= RIGHT: FORM ================= -->
    <div class="auth-panel">
      <div class="auth-brand">Glow<span>Beauty</span></div>

      <h1 class="auth-title"><?= e(t('register_eyebrow')) ?> <em><?= e(t('register_title')) ?></em></h1>
      <p class="auth-sub"><?= e(t('register_sub')) ?></p>

      <?php if ($error): ?>
        <div class="auth-alert auth-alert--error">⚠ <?= e($error) ?></div>
      <?php endif; ?>

      <form class="auth-form" method="post" novalidate>
        <div class="field">
          <label class="lbl" for="reg-name"><?= e(t('register_name')) ?><span class="req">*</span></label>
          <div class="auth-input">
            <span class="ico">👤</span>
            <input id="reg-name" type="text" name="name" required
                   value="<?= e($_POST['name'] ?? '') ?>" />
          </div>
        </div>

        <div class="field">
          <label class="lbl" for="reg-email"><?= e(t('email')) ?><span class="req">*</span></label>
          <div class="auth-input">
            <span class="ico">✉️</span>
            <input id="reg-email" type="email" name="email" required
                   value="<?= e($_POST['email'] ?? '') ?>" />
          </div>
        </div>

        <div class="auth-row">
          <div class="field">
            <label class="lbl" for="reg-phone"><?= e(t('phone')) ?><span class="req">*</span></label>
            <div class="auth-input">
              <span class="ico">📱</span>
              <input id="reg-phone" type="tel" name="phone" required
                     placeholder="07XX XXX XXX"
                     value="<?= e($_POST['phone'] ?? '') ?>" />
            </div>
          </div>

          <div class="field">
            <label class="lbl" for="reg-county"><?= e(t('county')) ?></label>
            <div class="auth-input">
              <span class="ico">📍</span>
              <select id="reg-county" name="county">
                <option value=""><?= e(t('select_county')) ?></option>
                <?php foreach (['Nairobi','Kiambu','Mombasa','Kisumu','Nakuru','Eldoret','Machakos','Other'] as $c): ?>
                  <option <?= (($_POST['county'] ?? '') === $c) ? 'selected' : '' ?>><?= e($c) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>

        <div class="field">
          <label class="lbl" for="reg-pass"><?= e(t('register_pass')) ?><span class="req">*</span></label>
          <div class="auth-input">
            <span class="ico">🔒</span>
            <input id="reg-pass" type="password" name="password" minlength="8" required />
            <button type="button" class="toggle" data-toggle="reg-pass" aria-label="Show password">👁</button>
          </div>
          <p class="auth-hint"><?= e(t('register_short')) ?></p>
        </div>

        <div class="field">
          <label class="lbl" for="reg-pass2"><?= e(t('register_pass2')) ?><span class="req">*</span></label>
          <div class="auth-input">
            <span class="ico">🔒</span>
            <input id="reg-pass2" type="password" name="password2" minlength="8" required />
            <button type="button" class="toggle" data-toggle="reg-pass2" aria-label="Show password">👁</button>
          </div>
        </div>

        <button class="auth-btn" type="submit"><?= e(t('register_btn')) ?></button>

        <p class="auth-alt">
          <?= e(t('register_have')) ?>
          <a href="login"><?= e(t('register_login')) ?></a>
        </p>
      </form>
    </div>

  </div>
</section>

<script>
(function () {
  const slides  = document.querySelectorAll('.auth-slide');
  const dots    = document.querySelectorAll('#authDots span');
  const caption = document.getElementById('authCaption');
  const data = <?= json_encode(array_map(fn($s) => [
      'title' => $s['title'],
      'text'  => $s['text'],
  ], $slides), JSON_UNESCAPED_UNICODE) ?>;

  let current = 0;
  const DURATION = 3000;

  function show(i) {
    slides.forEach((el, k) => el.classList.toggle('is-active', k === i));
    dots.forEach((el, k)   => el.classList.toggle('is-active', k === i));
    caption.querySelector('h3').textContent = data[i].title;
    caption.querySelector('p').textContent  = data[i].text;
    current = i;
  }

  setInterval(() => show((current + 1) % slides.length), DURATION);

  dots.forEach(dot => {
    dot.addEventListener('click', () => show(parseInt(dot.dataset.index, 10)));
  });

  document.querySelectorAll('.toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = document.getElementById(btn.dataset.toggle);
      if (!input) return;
      const s = input.type === 'password';
      input.type = s ? 'text' : 'password';
      btn.textContent = s ? '🙈' : '👁';
    });
  });
})();
</script>

<?php include __DIR__ . '/footer.php'; ?>