<?php
require_once __DIR__ . '/db.php';

session_start();
if (!empty($_SESSION['admin_id'])) {
    header('Location: admin-dashboard.php');
    exit;
}

$error = null;

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';

    $stmt = db()->prepare("SELECT * FROM cosmetics_admins WHERE username = ? LIMIT 1");
    $stmt->execute([$u]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($p, $admin['pass_hash'])) {
        $_SESSION['admin_id']   = (int)$admin['id'];
        $_SESSION['admin_user'] = $admin['username'];
        header('Location: admin-dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Login — GlowBeauty</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="admin.css" />
</head>
<body>
  <div class="admin-login">
    <div class="admin-login__card">
      <h1 class="admin-login__brand">Glow<span>Beauty</span></h1>
      <p class="admin-login__sub">Admin Dashboard</p>

      <?php if ($error): ?>
        <div class="admin-alert admin-alert--error"><?= e($error) ?></div>
      <?php endif; ?>

      <form class="admin-form" method="post">
        <label>Username
          <input type="text" name="username" required autofocus />
        </label>
        <label>Password
          <input type="password" name="password" required />
        </label>
        <button class="admin-btn admin-btn--primary" type="submit" style="width:100%;justify-content:center;">Sign In</button>
      </form>
    </div>
  </div>
</body>
</html>