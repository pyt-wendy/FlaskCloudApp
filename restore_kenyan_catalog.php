<?php
require_once 'includes/db.php';

$images = [
    // Healthcare & Pharmacy
    'face_mask.jpg' => 'https://d16zmt6hgq1jhj.cloudfront.net/product/12586/p3jRnxupM6PPr6ZpXfN0f9P.jpg',
    'dettol.jpg' => 'https://d16zmt6hgq1jhj.cloudfront.net/product/1138/8mK15gWAsD9vunvPNDfUic7p8UuWqS7vG5zX7p9P.jpg',
    'geisha.jpg' => 'https://d16zmt6hgq1jhj.cloudfront.net/product/16503/rk45x17vclYV8Wv599p2C2OqUuIAtW97X66X8p9P.jpg',
    'sunscreen.jpg' => 'https://d16zmt6hgq1jhj.cloudfront.net/product/15743/e8fU_S_Y4x0.jpg', // Sunblock style
    'hand_sanitizer.jpg' => 'https://d16zmt6hgq1jhj.cloudfront.net/product/12582/hand_sanitizer.jpg',
    
    // Groceries & Households
    'weetabix.jpg' => 'https://d16zmt6hgq1jhj.cloudfront.net/product/13746/EdZgDZBtHdjfpMqPL7zEc1sbKW7OYFOWUox5n01P.png',
    'ariel.jpg' => 'https://d16zmt6hgq1jhj.cloudfront.net/product/9204/Ariel%20Original%20Perfume%20Detergent%201Kg.jpg',
    'velvex.jpg' => 'https://d16zmt6hgq1jhj.cloudfront.net/product/1253/yS526GWA3I65uS76i656MAr8v4Xz7p9P.jpg',
    'energy_drink.jpg' => 'https://d16zmt6hgq1jhj.cloudfront.net/product/13203/uU3D3GWAOYvUunX7ODm6C7p9P.jpg',
    'coleslaw.jpg' => 'https://d16zmt6hgq1jhj.cloudfront.net/product/13217/SwB2Wb3u2iIdq7UfO0AInVdG5p5M5r8S3GvY9p9P.jpg',
    
    // Electronics
    'smartphone_x.jpg' => 'https://cdn.mafrservices.com/sys-master-root/hfe/h79/46884146184222/190226_main.jpg',
    'laptop_pro.jpg' => 'https://cdn.mafrservices.com/sys-master-root/h4f/h08/46327849811998/186813_main.jpg'
];

$dir = 'uploads/';
if (!is_dir($dir)) mkdir($dir);

// Reliable User-Agent for Kenyan CDNs
ini_set('user_agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

echo "<h2>Restoring Authentic Kenyan Catalog Assets</h2>";

$mapping = [
    'Reusable Face Masks' => 'face_mask.jpg',
    'Dettol Antiseptic 250ml' => 'dettol.jpg',
    'Geisha Bath Soap 90g' => 'geisha.jpg',
    'Sunscreen SPF 50' => 'sunscreen.jpg',
    'Hand Sanitizer 500ml' => 'hand_sanitizer.jpg',
    'Weetabix Cereal 400g' => 'weetabix.jpg',
    'Ariel Washing Powder 1kg' => 'ariel.jpg',
    'Velvex Toilet Paper 4pk' => 'velvex.jpg',
    'Energy Drink (500ml)' => 'energy_drink.jpg',
    'Coleslaw Large' => 'coleslaw.jpg',
    'Smartphone X' => 'smartphone_x.jpg',
    'Laptop Pro' => 'laptop_pro.jpg'
];

foreach ($images as $filename => $url) {
    echo "Downloading $filename... ";
    $content = @file_get_contents($url);
    if ($content !== false && strlen($content) > 100) {
        file_put_contents($dir . $filename, $content);
        echo "<span style='color:green;'>SUCCESS</span><br>";
    } else {
        echo "<span style='color:red;'>FAILED (URL: $url)</span><br>";
    }
}

echo "<h3>Updating Database Sync...</h3>";

foreach ($mapping as $name => $img) {
    $name_esc = mysqli_real_escape_string($con, $name);
    $res = mysqli_query($con, "UPDATE products SET image = '$img' WHERE name = '$name_esc'");
    if ($res && mysqli_affected_rows($con) > 0) {
        echo "Updated $name -> $img<br>";
    } else {
        echo "Could not find/update product: $name<br>";
    }
}

echo "<br><b>Authentic Restoration Complete!</b>";
?>
