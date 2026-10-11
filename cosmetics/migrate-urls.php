<?php
/* =========================================================
   GlowBeauty — ONE-TIME MIGRATION
   Convert hardcoded .php links into url() calls.
   ---------------------------------------------
   USAGE:
     1. Upload to project root as migrate-urls.php
     2. Open  http://localhost/cosmetics/migrate-urls.php
     3. Review report
     4. DELETE THIS FILE
   ========================================================= */

// Prevent accidental re-runs after migration is done
if (!isset($_GET['confirm']) || $_GET['confirm'] !== 'yes') {
    echo '<h2>Migrate .php links to url()</h2>';
    echo '<p>This will rewrite all .php files in this folder.</p>';
    echo '<p><strong>Back up your project first!</strong></p>';
    echo '<p><a href="?confirm=yes" style="padding:10px 20px;background:#e91e63;color:#fff;text-decoration:none;border-radius:6px;">Run migration →</a></p>';
    exit;
}

set_time_limit(0);

$root = __DIR__;

/* Files to skip */
$skipFiles = [
    'migrate-urls.php',
    'config.php',
    'db.php',
    'lang.php',
    'products.php',
];

/* Collect all .php files in the root only (not subfolders) */
$files = glob($root . '/*.php');

$report = [];

foreach ($files as $file) {
    $name = basename($file);
    if (in_array($name, $skipFiles, true)) {
        $report[$name] = ['status' => 'skipped', 'changes' => 0];
        continue;
    }

    $src  = file_get_contents($file);
    $orig = $src;
    $changes = 0;

    /* ---- A. href="xxx.php?y=z"  ->  href="<?= e(url('xxx?y=z')) ?>" ---- */
    $src = preg_replace_callback(
        '/href="([a-z0-9_-]+)\.php\?([^"]+)"/i',
        function ($m) use (&$changes) {
            $changes++;
            $slug = $m[1] . '?' . $m[2];
            return 'href="<?= e(url(\'' . addslashes($slug) . '\')) ?>"';
        },
        $src
    );

    /* ---- B. href="xxx.php"  ->  href="<?= e(url('xxx')) ?>" ---- */
    $src = preg_replace_callback(
        '/href="([a-z0-9_-]+)\.php"/i',
        function ($m) use (&$changes) {
            $changes++;
            return 'href="<?= e(url(\'' . $m[1] . '\')) ?>"';
        },
        $src
    );

    /* ---- C. action="xxx.php?y=z"  ->  action="<?= e(url('xxx?y=z')) ?>" ---- */
    $src = preg_replace_callback(
        '/action="([a-z0-9_-]+)\.php\?([^"]+)"/i',
        function ($m) use (&$changes) {
            $changes++;
            $slug = $m[1] . '?' . $m[2];
            return 'action="<?= e(url(\'' . addslashes($slug) . '\')) ?>"';
        },
        $src
    );

    /* ---- D. action="xxx.php"  ->  action="<?= e(url('xxx')) ?>" ---- */
    $src = preg_replace_callback(
        '/action="([a-z0-9_-]+)\.php"/i',
        function ($m) use (&$changes) {
            $changes++;
            return 'action="<?= e(url(\'' . $m[1] . '\')) ?>"';
        },
        $src
    );

    if ($changes > 0 && $src !== $orig) {
        // Backup
        @copy($file, $file . '.bak');

        file_put_contents($file, $src);
        $report[$name] = ['status' => 'updated', 'changes' => $changes];
    } else {
        $report[$name] = ['status' => 'clean', 'changes' => 0];
    }
}
?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Migration report</title>
  <style>
    body { font: 14px/1.5 -apple-system, system-ui, sans-serif; max-width: 720px; margin: 40px auto; padding: 0 20px; color: #1f2937; }
    h1 { font-size: 22px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid #e5e7eb; }
    th { background: #f3f4f6; font-size: 13px; text-transform: uppercase; letter-spacing: .5px; }
    .updated { color: #16a34a; font-weight: 600; }
    .clean   { color: #6b7280; }
    .skipped { color: #b45309; }
    .danger  { background: #fee2e2; border-left: 4px solid #ef4444; padding: 14px 18px; border-radius: 6px; margin-top: 24px; }
  </style>
</head>
<body>
  <h1>Migration report</h1>
  <table>
    <thead>
      <tr><th>File</th><th>Status</th><th>Links changed</th></tr>
    </thead>
    <tbody>
      <?php foreach ($report as $file => $info): ?>
        <tr>
          <td><?= htmlspecialchars($file) ?></td>
          <td class="<?= $info['status'] ?>">
            <?php
              echo match ($info['status']) {
                'updated' => '✔ Updated',
                'clean'   => '— Clean',
                'skipped' => '⏭ Skipped',
                default   => $info['status'],
              };
            ?>
          </td>
          <td><?= $info['changes'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="danger">
    <strong>Delete this file now.</strong><br>
    The migration is complete. A <code>.bak</code> backup of each modified file was saved — you can safely remove them once you've verified everything works.
  </div>
</body>
</html>