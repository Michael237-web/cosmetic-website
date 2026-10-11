<?php
require_once __DIR__ . '/db.php';

function get_products(): array {
    static $cache = null;
    if ($cache !== null) return $cache;

    $rows  = db()->query("SELECT * FROM cosmetics_products ORDER BY created_at DESC")->fetchAll();
    $cache = array_map('hydrate_product', $rows);
    return $cache;
}

function hydrate_product(array $r): array {
    return [
        'id'          => (string)$r['id'],              // <-- string, not int
        'brand'       => $r['brand'],
        'name'        => $r['name'],
        'category'    => $r['category'],
        'price'       => (float)$r['price'],
        'old'         => (float)($r['old_price'] ?? 0),
        'rating'      => (float)$r['rating'],
        'reviews'     => (int)$r['reviews'],
        'emoji'       => $r['emoji'] ?? '🛍️',
        'image_url'   => $r['image_url'] ?? null,
        'discount'    => $r['discount'] ?? '',
        'size'        => $r['size'] ?? '',
        'skin_type'   => !empty($r['skin_type']) ? explode(',', $r['skin_type']) : [],
        'concerns'    => !empty($r['concerns'])  ? explode(',', $r['concerns'])  : [],
        'in_stock'    => (bool)($r['in_stock'] ?? true),
        'benefits'    => !empty($r['benefits'])  ? explode("\n", $r['benefits']) : [],
        'description' => $r['description'] ?? '',
        'ingredients' => $r['ingredients'] ?? '',
        'how_to_use'  => $r['how_to_use']  ?? '',
    ];
}

function find_product(string $id): ?array {
    $stmt = db()->prepare("SELECT * FROM cosmetics_products WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ? hydrate_product($row) : null;
}

function products_by_category(string $slug): array {
    if (!$slug || $slug === 'all') return get_products();
    $stmt = db()->prepare("SELECT * FROM cosmetics_products WHERE category = ? ORDER BY created_at DESC");
    $stmt->execute([$slug]);
    return array_map('hydrate_product', $stmt->fetchAll());
}

function get_categories(): array {
    return [
        'body-care'   => ['label' => 'Body Care',   'icon' => '🧴', 'desc' => 'Lotions, creams & oils',    'img' => 'https://images.unsplash.com/photo-1556228720-195a672e8a03?auto=format&fit=crop&w=600&q=80'],
        'face-care'   => ['label' => 'Face Care',   'icon' => '✨', 'desc' => 'Cleansers, serums & SPF',   'img' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=600&q=80'],
        'makeup'      => ['label' => 'Makeup',      'icon' => '💄', 'desc' => 'Face, lips & eyes',         'img' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=600&q=80'],
        'hair-care'   => ['label' => 'Hair Care',   'icon' => '💇‍♀️', 'desc' => 'Shampoo, oils & masks',   'img' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?auto=format&fit=crop&w=600&q=80'],
        'fragrance'   => ['label' => 'Fragrance',   'icon' => '🌸', 'desc' => 'Perfumes & mists',          'img' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=600&q=80'],
        'nails'       => ['label' => 'Nails',       'icon' => '💅', 'desc' => 'Polish & treatments',       'img' => 'https://images.unsplash.com/photo-1604654894610-df63bc536371?auto=format&fit=crop&w=600&q=80'],
        'bath'        => ['label' => 'Bath & Self-Care','icon'=>'🛁','desc' => 'Salts, bombs & candles',    'img' => 'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?auto=format&fit=crop&w=600&q=80'],
        'accessories' => ['label' => 'Accessories', 'icon' => '🎁', 'desc' => 'Brushes, bags & tools',     'img' => 'https://images.unsplash.com/photo-1522338242992-e1a54906a8da?auto=format&fit=crop&w=600&q=80'],
    ];
}

function get_services(): array {
    return db()->query("SELECT * FROM cosmetics_services WHERE is_active = 1 ORDER BY sort_order")->fetchAll();
}