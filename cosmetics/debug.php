<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
echo "PHP version: " . phpversion() . "<br>";

/* Test each include one by one */
$files = ['env.php', 'db.php', 'products.php'];
foreach ($files as $f) {
    $path = __DIR__ . '/' . $f;
    echo "Checking <code>$f</code>: ";
    if (file_exists($path)) {
        echo "✓ exists<br>";
    } else {
        echo "❌ MISSING<br>";
    }
}

/* Try database connection */
echo "<hr>Testing DB connection...<br>";
try {
    require_once __DIR__ . '/db.php';
    $pdo = db();
    $count = $pdo->query("SELECT COUNT(*) FROM cosmetics_products")->fetchColumn();
    echo "✓ Connected. Products in DB: <strong>$count</strong><br>";
} catch (Throwable $e) {
    echo "❌ DB ERROR: " . htmlspecialchars($e->getMessage()) . "<br>";
}