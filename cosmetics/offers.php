<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

// Force language detection/redirect BEFORE any output
current_lang();

$page_title = t('offers_title') . ' — ' . SITE_NAME;
$subError = null;
$subOk    = false;

/* ---------- Handle subscription (AJAX + normal POST) ---------- */
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && ($_POST['action'] ?? '') === 'subscribe') {

    $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
              && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $subError = t('offers_bad_email');
    } else {
        try {
            $stmt = db()->prepare("INSERT INTO cosmetics_subscribers (email) VALUES (?)");
            $stmt->execute([$email]);
            $subOk = true;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $subError = t('offers_exists');
            } else {
                throw $e;
            }
        }
    }

    /* If AJAX, return JSON and stop — the JS will show + fade the message. */
    if ($isAjax) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'ok'    => $subOk,
            'error' => $subError,
            'msg'   => $subOk ? t('offers_success') : ($subError ?: ''),
        ]);
        exit;
    }
}

include __DIR__ . '/header.php';
?>

<section class="section">
  <div class="container">
    <header class="section__head">
      <p class="section__eyebrow"><?= e(t('offers_eyebrow')) ?></p>
      <h2 class="section__title"><?= e(t('offers_title')) ?></h2>
      <p class="section__subtitle"><?= e(t('offers_sub')) ?></p>
    </header>

    <!-- Flash Sale -->
    <div style="background:linear-gradient(135deg,#fff 0%,#fce4ec 100%);border-left:5px solid #d4a574;border-radius:16px;padding:36px;margin-bottom:24px;">
      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:20px;">
        <div>
          <span style="display:inline-block;font-size:12px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:#d4a574;margin-bottom:8px;">
            <?= e(t('offers_flash')) ?>
          </span>
          <h3 style="font-family:var(--font-display);font-size:26px;color:#1f1f1f;margin-bottom:6px;">
            <?= e(t('offers_flash_ends')) ?>
          </h3>
          <p style="color:#6b6b6b;font-size:14px;">
            <?= e(t('offers_flash_sub')) ?>
          </p>
        </div>
        <div class="countdown" id="countdown" style="font-size:34px;">02 : 15 : 43</div>
      </div>
    </div>

    <!-- Deals grid -->
    <div class="deals-grid">
      <article class="deal-card">
        <span class="deal-card__tag"><?= e(t('body_care')) ?></span>
        <h3><?= e(t('offers_deal_1_title')) ?></h3>
        <p><?= t('offers_deal_1_desc') /* contains <strong> so no e() */ ?></p>
      </article>
      <article class="deal-card">
        <span class="deal-card__tag"><?= e(t('fragrance')) ?></span>
        <h3><?= e(t('offers_deal_2_title')) ?></h3>
        <p><?= t('offers_deal_2_desc') ?></p>
      </article>
      <article class="deal-card">
        <span class="deal-card__tag"><?= e(t('makeup')) ?></span>
        <h3><?= e(t('offers_deal_3_title')) ?></h3>
        <p><?= t('offers_deal_3_desc') ?></p>
      </article>
      <article class="deal-card">
        <span class="deal-card__tag"><?= e(t('face_care')) ?></span>
        <h3><?= e(t('offers_deal_4_title')) ?></h3>
        <p><?= t('offers_deal_4_desc') ?></p>
      </article>
    </div>

    <!-- Bundles -->
    <header class="section__head" style="margin-top:70px;">
      <h3 class="section__title" style="font-size:28px;"><?= e(t('bundles_title')) ?></h3>
      <p class="section__subtitle"><?= e(t('bundles_sub')) ?></p>
    </header>

    <div class="bundles-grid">
      <?php
      $bundles = [
        [
          'name'  => t('bundle_1_name'),
          'price' => 3499,
          'old'   => 4200,
          'items' => [t('bundle_1_item_1'), t('bundle_1_item_2'), t('bundle_1_item_3'), t('bundle_1_item_4')],
        ],
        [
          'name'  => t('bundle_2_name'),
          'price' => 2999,
          'old'   => 3800,
          'items' => [t('bundle_2_item_1'), t('bundle_2_item_2'), t('bundle_2_item_3'), t('bundle_2_item_4')],
        ],
        [
          'name'  => t('bundle_3_name'),
          'price' => 1999,
          'old'   => 2600,
          'items' => [t('bundle_3_item_1'), t('bundle_3_item_2'), t('bundle_3_item_3'), t('bundle_3_item_4')],
        ],
      ];
      foreach ($bundles as $b): ?>
        <article class="bundle-card">
          <h3><?= e($b['name']) ?></h3>
          <ul><?php foreach ($b['items'] as $i): ?><li><?= e($i) ?></li><?php endforeach; ?></ul>
          <div class="bundle-card__price">
            <?= money($b['price']) ?> <small><?= money($b['old']) ?></small>
          </div>
          <form action="cart.php" method="post">
            <input type="hidden" name="action" value="add_bundle" />
            <input type="hidden" name="name" value="<?= e($b['name']) ?>" />
            <input type="hidden" name="price" value="<?= (int)$b['price'] ?>" />
            <button class="btn btn--primary" type="submit"><?= e(t('add_to_cart')) ?></button>
          </form>
        </article>
      <?php endforeach; ?>
    </div>

    <!-- Newsletter -->
    <div style="margin-top:80px;background:#1f1f1f;color:#fff;padding:50px;border-radius:20px;text-align:center;">
      <h3 style="font-family:var(--font-display);font-size:28px;margin-bottom:10px;">
        <?= e(t('offers_subscribe')) ?>
      </h3>
      <p style="color:#cfcfcf;margin-bottom:26px;font-size:14.5px;">
        <?= e(t('offers_subscribe_p')) ?>
      </p>

      <!-- Message placeholder — filled by PHP on POST, or by JS on AJAX -->
      <p id="subscribeMsg"
         style="font-weight:500;margin-bottom:14px;opacity:0;transition:opacity .45s ease;pointer-events:none;"
         data-ok="<?= $subOk ? '1' : '0' ?>"
         data-err="<?= $subError ? e($subError) : '' ?>"
         data-okmsg="<?= e(t('offers_success')) ?>">
        <?php if ($subOk): ?>
          <span style="color:#81c784;">✓ <?= e(t('offers_success')) ?></span>
        <?php elseif ($subError): ?>
          <span style="color:#ef9a9a;">⚠ <?= e($subError) ?></span>
        <?php endif; ?>
      </p>

      <form method="post" id="subscribeForm"
            style="max-width:460px;margin:0 auto;display:flex;gap:10px;flex-wrap:wrap;">
        <input type="hidden" name="action" value="subscribe" />
        <input type="email" name="email" required
               placeholder="<?= e(t('offers_email')) ?>"
               style="flex:1;min-width:220px;padding:14px 18px;border-radius:40px;border:none;font-family:inherit;font-size:14px;outline:none;" />
        <button type="submit" class="btn btn--primary"><?= e(t('offers_btn')) ?></button>
      </form>
    </div>
  </div>
</section>

<script>
(function () {
  const form = document.getElementById('subscribeForm');
  const msg  = document.getElementById('subscribeMsg');
  if (!form || !msg) return;

  const FADE_AFTER   = 3000;   // show for 3 seconds
  const FADE_OUT_MS  = 500;    // then fade out over 0.5s
  let   hideTimer    = null;
  let   clearTimer   = null;

  function showMessage(text, isOk) {
    // Clear any pending timers from previous submissions
    clearTimeout(hideTimer);
    clearTimeout(clearTimer);

    // Set colour + text
    msg.style.color = isOk ? '#81c784' : '#ef9a9a';
    msg.textContent = (isOk ? '✓ ' : '⚠ ') + text;

    // Fade in
    msg.style.transition = 'opacity .3s ease';
    msg.style.opacity = '1';

    // Fade out after 3 seconds
    hideTimer = setTimeout(() => {
      msg.style.transition = 'opacity ' + (FADE_OUT_MS / 1000) + 's ease';
      msg.style.opacity = '0';

      // Once fully hidden, wipe the text so it doesn't block the layout
      clearTimer = setTimeout(() => { msg.textContent = ''; }, FADE_OUT_MS + 50);
    }, FADE_AFTER);
  }

  /* If PHP already produced a message (non-AJAX fallback), fade it in then out. */
  const initialText = msg.textContent.trim();
  if (initialText) {
    msg.style.opacity = '0';
    // Tiny delay so the browser paints the opacity:0 first, then we animate in.
    requestAnimationFrame(() => requestAnimationFrame(() => {
      showMessage(
        initialText.replace(/^[✓⚠]\s*/, ''),
        msg.dataset.ok === '1'
      );
    }));
  }

  /* Intercept submission and use AJAX so the page doesn't reload
     and we can animate the message. Falls back to normal submit
     if fetch isn't available. */
  if (typeof fetch === 'function') {
    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(form);

      fetch(window.location.pathname, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      })
      .then(r => r.json())
      .then(data => {
        if (data.ok) {
          showMessage(data.msg || msg.dataset.okmsg, true);
          form.reset();
        } else {
          showMessage(data.error || data.msg || '', false);
        }
      })
      .catch(() => {
        // Network error → fall back to a normal submit
        form.removeEventListener('submit', arguments.callee);
        form.submit();
      });
    });
  }
})();
</script>

<?php include __DIR__ . '/footer.php'; ?>