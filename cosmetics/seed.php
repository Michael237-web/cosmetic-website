<?php
require_once __DIR__ . '/db.php';

$products = [
    ['nivea-cocoa-body-lotion','NIVEA','Cocoa Butter Body Lotion','body-care',1200,1500,4.8,126,'🧴','20% OFF','400ml',
     'Dry,Normal','hydration,glow',
     "Deeply moisturises dry skin\nLeaves skin feeling soft for 24 hours\nEnriched with cocoa butter and vitamin E",
     'A rich, fast-absorbing body lotion that delivers long-lasting hydration.',
     'Aqua, Glycerin, Cetearyl Alcohol, Theobroma Cacao Seed Butter, Tocopheryl Acetate, Parfum.',
     'Apply generously to clean, dry skin morning and evening.'],
    ['dove-deep-moisture-wash','DOVE','Deep Moisture Body Wash','body-care',850,1000,4.7,94,'🛁','15% OFF','500ml',
     'Dry,Normal,Sensitive','hydration',
     "Gentle daily cleanser\nMoisturising formula with NutriumMoisture\nLeaves skin soft, never tight",
     'A nourishing body wash that cleanses without stripping.',
     'Aqua, Sodium Lauroyl Isethionate, Glycerin, Stearic Acid, Lauric Acid, Parfum.',
     'Pour onto a wet loofah, lather, massage over body, then rinse thoroughly.'],
    ['cerave-moisturizing-cream','CeraVe','Moisturizing Cream','face-care',2200,2500,4.9,312,'✨','12% OFF','454g',
     'Dry,Sensitive,Normal','hydration,even-tone',
     "Developed with dermatologists\nContains 3 essential ceramides\n24-hour hydration with hyaluronic acid",
     'A rich, non-greasy cream that restores the skin barrier.',
     'Aqua, Glycerin, Cetearyl Alcohol, Ceramide NP, Hyaluronic Acid.',
     'Apply to face and body as often as needed.'],
    ['ordinary-niacinamide','The Ordinary','Niacinamide 10% + Zinc 1%','face-care',1800,2100,4.8,208,'💧','14% OFF','30ml',
     'Oily,Combination','acne,even-tone',
     "Reduces appearance of blemishes\nBalances visible sebum activity\nBrightens uneven skin tone",
     'A high-strength vitamin and mineral blemish formula.',
     'Aqua, Niacinamide, Pentylene Glycol, Zinc PCA.',
     'Apply a few drops to clean skin morning and evening.'],
    ['maybelline-fit-me','Maybelline','Fit Me Matte + Poreless Foundation','makeup',1500,1800,4.6,421,'💄','17% OFF','30ml',
     'Oily,Combination,Normal','even-tone',
     "Matte, poreless finish\nLightweight, breathable coverage\nAvailable in multiple shades",
     'A mattifying foundation that blurs pores and controls shine.',
     'Aqua, Dimethicone, Talc, Isododecane, Silica, Titanium Dioxide.',
     'Apply a small amount to the centre of the face and blend outward.'],
    ['nivea-rose-lotion','NIVEA','Rose Care Body Lotion','body-care',950,1200,4.7,78,'🌹','21% OFF','400ml',
     'Normal,Dry','hydration,glow',
     "Enriched with rose extract\nLight, feminine fragrance\n24-hour moisture",
     'A delicately scented body lotion infused with rose extract.',
     'Aqua, Glycerin, Cetearyl Alcohol, Rosa Damascena Flower Extract, Parfum.',
     'Massage into skin daily after showering.'],
    ['garnier-vitamin-c','Garnier','Vitamin C Brightening Serum','face-care',1600,1900,4.5,156,'🍊','16% OFF','30ml',
     'Normal,Combination','glow,even-tone',
     "Brightens dull skin\nFades dark spots over time\nAntioxidant-rich formula",
     'A lightweight brightening serum powered by vitamin C.',
     'Aqua, Ascorbic Acid, Niacinamide, Glycerin, Salicylic Acid.',
     'Apply 3–4 drops to clean skin each morning.'],
    ['vaseline-aloe-gel','Vaseline','Aloe Fresh Body Gel','body-care',700,900,4.6,88,'🌿','22% OFF','200ml',
     'Normal,Oily,Combination','hydration',
     "Cooling aloe vera gel\nNon-greasy, fast-absorbing\nPerfect for warm weather",
     'A refreshing body gel with aloe vera that hydrates without heaviness.',
     'Aqua, Aloe Barbadensis Leaf Juice, Glycerin, Carbomer, Parfum.',
     'Apply to skin after sun exposure or whenever skin feels dry.'],
];

$stmt = db()->prepare("
  INSERT INTO cosmetics_products
    (id, brand, name, category, price, old_price, rating, reviews, emoji,
     discount, size, skin_type, concerns, benefits, description, ingredients, how_to_use)
  VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
  ON DUPLICATE KEY UPDATE name = VALUES(name)
");

foreach ($products as $p) {
    $stmt->execute($p);
}
echo "Seeded " . count($products) . " products. <a href='index.php'>Go to site</a>";