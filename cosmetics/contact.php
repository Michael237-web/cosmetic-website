<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

$page_title = t('contact_title') . ' — ' . SITE_NAME;
$sent  = false;
$error = null;

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$subject || !$message) {
        $error = t('contact_error');
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = t('contact_bad_email');
    } else {
        $stmt = db()->prepare("
            INSERT INTO cosmetics_contacts (name, email, subject, message)
            VALUES (?,?,?,?)
        ");
        $stmt->execute([$name, $email, $subject, $message]);
        $sent = true;
    }
}

include __DIR__ . '/header.php';
?>

<section class="section">
  <div class="container" style="max-width:720px;">
    <header class="section__head">
      <p class="section__eyebrow"><?= e(t('contact_eyebrow')) ?></p>
      <h2 class="section__title"><?= e(t('contact_title')) ?></h2>
      <p class="section__subtitle"><?= e(t('contact_sub')) ?></p>
    </header>

    <?php if ($sent): ?>
      <p style="text-align:center;color:#2e7d32;font-weight:500;margin-bottom:24px;">
        ✓ <?= e(t('contact_sent')) ?>
      </p>
    <?php elseif ($error): ?>
      <p style="text-align:center;color:#c2185b;font-weight:500;margin-bottom:24px;">
        ⚠ <?= e($error) ?>
      </p>
    <?php endif; ?>

    <form class="checkout-form" method="post">
      <div class="form-row">
        <label><?= e(t('full_name')) ?>
          <input type="text" name="name" required />
        </label>
        <label><?= e(t('email')) ?>
          <input type="email" name="email" required />
        </label>
      </div>
      <label><?= e(t('subject')) ?>
        <input type="text" name="subject" required />
      </label>
      <label><?= e(t('message')) ?>
        <textarea name="message" rows="6" required></textarea>
      </label>
      <button class="btn btn--primary btn--full" type="submit"><?= e(t('send_message')) ?></button>
    </form>

    <div style="margin-top:50px;text-align:center;color:#6b6b6b;line-height:2;">
      <p>📞 +254 700 000 000</p>
      <p>✉️ hello@glowbeauty.co.ke</p>
      <p>📍 Nairobi, Kenya</p>
    </div>
  </div>
</section>

<?php include __DIR__ . '/footer.php'; ?>