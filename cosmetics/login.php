<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

/* Force language detection/redirect BEFORE any output */
current_lang();

if (!empty($_SESSION['customer_id'])) {
    redirect('index.php');
}

/* ✅ Read flash messages from registration */
$flash_success = flash('success');
$flash_error   = flash('error');

$page_title = t('login_title') . ' — ' . SITE_NAME;
$auth_page  = true;

$error = null;

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $pass  = $_POST['password'] ?? '';

    if (!$email || !$pass) {
        $error = t('register_error');
    } else {
        $stmt = db()->prepare("SELECT * FROM cosmetics_customers WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $customer = $stmt->fetch();

        if ($customer && password_verify($pass, $customer['pass_hash'])) {
            $_SESSION['customer_id']   = (int)$customer['id'];
            $_SESSION['customer_name'] = $customer['full_name'];

            /* ✅ Welcome flash on next page */
            $_SESSION['flash']['success'] = 'Welcome back, ' . $customer['full_name'] . '!';

            redirect('index.php');
        } else {
            $error = t('login_bad_creds');
        }
    }
}

/* Slideshow — translated titles/texts */
$slides = [
    [
        'img'   => 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=1200&q=80',
        'title' => t('login_slide_1_title'),
        'text'  => t('login_slide_1_text'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=1200&q=80',
        'title' => t('login_slide_2_title'),
        'text'  => t('login_slide_2_text'),
    ],
    [
        'img'   => 'https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&w=1200&q=80',
        'title' => t('login_slide_3_title'),
        'text'  => t('login_slide_3_text'),
    ],
];

include __DIR__ . '/header.php';
?>

<section class="auth-body">
  <div class="auth-shell">

    <div class="auth-media">
      <?php foreach ($slides as $i => $s): ?>
        <div class="auth-slide <?= $i === 0 ? 'is-active' : '' ?>"
             style="background-image:url('<?= e($s['img']) ?>');"></div>
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

    <div class="auth-panel">
      <div class="auth-brand">Glow<span>Beauty</span></div>

      <h1 class="auth-title"><?= e(t('login_eyebrow')) ?></h1>
      <p class="auth-sub"><?= e(t('login_sub')) ?></p>

      <?php if ($flash_success): ?>
        <div class="auth-alert auth-alert--success">
          ✅ <?= e($flash_success) ?>
        </div>
      <?php endif; ?>

      <?php if ($flash_error): ?>
        <div class="auth-alert auth-alert--error">
          ⚠ <?= e($flash_error) ?>
        </div>
      <?php endif; ?>

      <?php if ($error): ?>
        <div class="auth-alert auth-alert--error">⚠ <?= e($error) ?></div>
      <?php endif; ?>

      <form class="auth-form" method="post" novalidate>
        <div class="field">
          <label class="lbl" for="log-email"><?= e(t('email')) ?><span class="req">*</span></label>
          <div class="auth-input">
            <span class="ico">✉️</span>
            <input id="log-email" type="email" name="email" required
                   value="<?= e($_POST['email'] ?? '') ?>" />
          </div>
        </div>

        <div class="field">
          <label class="lbl" for="log-pass"><?= e(t('register_pass')) ?><span class="req">*</span></label>
          <div class="auth-input">
            <span class="ico">🔒</span>
            <input id="log-pass" type="password" name="password" required />
            <button type="button" class="toggle" data-toggle="log-pass" aria-label="Show password">👁</button>
          </div>
        </div>

        <button class="auth-btn" type="submit"><?= e(t('login_btn')) ?></button>

        <p class="auth-alt">
          <?= e(t('login_no_account')) ?>
          <a href="register"><?= e(t('login_create')) ?></a>
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
  function show(i) {
    slides.forEach((el, k) => el.classList.toggle('is-active', k === i));
    dots.forEach((el, k)   => el.classList.toggle('is-active', k === i));
    caption.querySelector('h3').textContent = data[i].title;
    caption.querySelector('p').textContent  = data[i].text;
    current = i;
  }
  setInterval(() => show((current + 1) % slides.length), 3000);
  dots.forEach(d => d.addEventListener('click', () => show(+d.dataset.index)));

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