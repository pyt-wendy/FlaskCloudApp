<?php
$images = [
    // Electronics
    'smartphone_x.jpg' => 'https://images.unsplash.com/photo-1592890288564-76628a30a657?fm=jpg&q=80&w=800',
    'laptop_pro.jpg' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?fm=jpg&q=80&w=800',
    'headphones.jpg' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?fm=jpg&q=80&w=800',
    'watch.jpg' => 'https://images.unsplash.com/photo-1544117518-3a763b65a443?fm=jpg&q=80&w=800',
    
    // Healthcare (Previous failures)
    'face_mask.jpg' => 'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?fm=jpg&q=80&w=800',
    'sunscreen.jpg' => 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?fm=jpg&q=80&w=800',
    'hand_sanitizer.jpg' => 'https://images.unsplash.com/photo-1584483766114-2cea6facdf57?fm=jpg&q=80&w=800',
    
    // Groceries (Replacing generic placeholders)
    'flour.jpg' => 'https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?fm=jpg&q=80&w=800',
    'vegetable_oil.jpg' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?fm=jpg&q=80&w=800',
    'washing_powder.jpg' => 'https://images.unsplash.com/photo-1610557892470-55d9e80c0bce?fm=jpg&q=80&w=800',
    'soap.jpg' => 'https://images.unsplash.com/photo-1600857062241-98e5dba7f214?fm=jpg&q=80&w=800',
    'salt.jpg' => 'https://images.unsplash.com/photo-1518110925495-5fe23e661fa1?fm=jpg&q=80&w=800',
    'margarine.jpg' => 'https://images.unsplash.com/photo-1589985270826-4b7bb135bc9d?fm=jpg&q=80&w=800'
];

$dir = 'uploads/';
if (!is_dir($dir)) mkdir($dir);

// Reliable User-Agent
ini_set('user_agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');

echo "Starting final asset fetching...\n";
foreach ($images as $filename => $url) {
    echo "Fetching $filename from $url...\n";
    $content = @file_get_contents($url);
    if ($content !== false && strlen($content) > 100) {
        file_put_contents($dir . $filename, $content);
        echo "Successfully saved $filename\n";
    } else {
        echo "Failed to download $filename. Skipping or using placeholder.\n";
    }
}
echo "All downloads complete!\n";
?>
