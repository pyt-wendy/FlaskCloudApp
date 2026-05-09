<?php
require_once 'includes/db.php';

echo "<h2>Linking Professional Photography to Products</h2>";

$mapping = [
    'Reusable Face Masks' => 'face_mask.jpg',
    'Hand Sanitizer 500ml' => 'hand_sanitizer.jpg',
    'Sunscreen SPF 50' => 'sunscreen.jpg',
    'Pet Bed Large' => 'pet_bed.jpg',
    'Energy Drink (500ml)' => 'energy_drink.jpg',
    'Premium Wine (750ml)' => 'wine_bottle.jpg',
    'Coleslaw Large' => 'coleslaw.jpg',
    'Steak & Chips Meal' => 'steak_chips.jpg',
    'Crispy Tacos (3pk)' => 'tacos.jpg',
    'Gaming Mouse' => 'gaming_mouse.jpg',
    '10-inch Tablet' => 'tablet.jpg',
    'Smart Watch Ultra' => 'smartwatch.jpg',
    'Heinz Tomato Ketchup 300ml' => 'ketchup.jpg',
    'Weetabix Cereal 400g' => 'cereal.jpg',
    'Dettol Antiseptic 250ml' => 'dettol.jpg',
    'Velvex Toilet Paper 4pk' => 'toilet_paper.jpg'
];

foreach ($mapping as $name => $img) {
    $name = mysqli_real_escape_string($con, $name);
    $res = mysqli_query($con, "UPDATE products SET image = '$img' WHERE name = '$name'");
    if ($res) {
        echo "Successfully updated $name -> $img<br>";
    } else {
        echo "Failed to update $name<br>";
    }
}

echo "<br><b>Database Update Complete!</b>";
?>
