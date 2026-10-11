<?php
/* =========================================================
   Minimal .env loader — no composer required
   ========================================================= */

function load_env(string $path): void {
    if (!file_exists($path)) return;
    foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') continue;
        if (!str_contains($line, '=')) continue;
        [$k, $v] = explode('=', $line, 2);
        $k = trim($k); $v = trim($v);
        $v = trim($v, "\"'");
        if (!getenv($k)) putenv("$k=$v");
    }
}
load_env(__DIR__ . '/.env');