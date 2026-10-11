<?php
/* =========================================================
   GlowBeauty — Global Configuration
   ========================================================= */

/* ---------- Error reporting (dev only — remove on live) ---------- */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* ---------- Session ---------- */
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

/* ---------- Constants ---------- */
define('SITE_NAME', 'GlowBeauty');
define('SITE_URL',  'http://localhost/cosmetics');
define('CURRENCY',  'KSh');
define('FREE_SHIP_THRESHOLD', 2000);

/* ---------- Language system ---------- */
require_once __DIR__ . '/lang.php';
current_lang();      // detects ?lang=xx, persists in session + cookie

/* =========================================================
   Pretty URLs — helper
   ========================================================= */
function url(string $path = ''): string
{
    $path = trim($path);
    $path = ltrim($path, '/');
    $path = preg_replace('/\.php$/i', '', $path);

    if ($path === '') {
        $path = 'index';
    }

    $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

    if ($base === '' || $base === '.') {
        $parsed = parse_url(SITE_URL);
        $base   = rtrim($parsed['path'] ?? '', '/');
    }

    return $base . '/' . $path;
}

function lang_url(string $path = '', ?string $lang = null): string
{
    $lang = $lang ?? current_lang();
    $u = url($path);
    $u .= (strpos($u, '?') === false ? '?' : '&') . 'lang=' . urlencode($lang);
    return $u;
}

/* ---------- Helpers ---------- */
function money($amount) {
    return CURRENCY . ' ' . number_format((float)$amount);
}

function e($str) {
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function redirect($path) {
    if (!preg_match('~^https?://~i', $path)) {
        $path = url($path);
    }
    header("Location: $path");
    exit;
}

/* ---------- Cart ---------- */
function cart_count() {
    return isset($_SESSION['cart'])
        ? array_sum(array_column($_SESSION['cart'], 'qty'))
        : 0;
}

function cart_total() {
    if (empty($_SESSION['cart'])) return 0;
    return array_reduce(
        $_SESSION['cart'],
        fn($sum, $i) => $sum + ($i['price'] * $i['qty']),
        0
    );
}

/* ---------- Flash messages (SINGLE DEFINITION) ---------- */
function flash($key, $msg = null) {
    if ($msg !== null) {
        $_SESSION['flash'][$key] = $msg;
        return;
    }
    $m = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $m;
}

/* ---------- Pretty-URL helper for the current page ---------- */
function current_pretty_url(): string
{
    $path = $_SERVER['SCRIPT_NAME'] ?? '/index';
    $path = preg_replace('/\.php$/i', '', $path);
    return $path;
}

/* =========================================================
   AUTH HELPERS
   ========================================================= */
function is_logged_in(): bool {
    return !empty($_SESSION['customer_id']);
}

function current_user_name(): string {
    return $_SESSION['customer_name'] ?? 'Guest';
}

function current_user_id(): int {
    return (int)($_SESSION['customer_id'] ?? 0);
}



