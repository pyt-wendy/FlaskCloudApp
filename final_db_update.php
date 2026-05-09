<?php
require_once 'includes/db.php';

echo "<h2>Executing Final Final Catalog Refinement</h2>";

$updates = [
    // Electronics
    ['name like "Smartphone X%"', 'image', 'smartphone_x.jpg'],
    ['name like "Laptop Pro%"', 'image', 'laptop_pro.jpg'],
    ['name like "Laptop Pro%"', 'price', 130000],
    ['name like "Laptop Pro%"', 'old_price', 150000],
    ['name like "Wireless Headphones%"', 'image', 'headphones.jpg'],
    
    // Healthcare
    ['name like "Reusable Face Masks%"', 'image', 'face_mask.jpg'],
    ['name like "Sunscreen SPF 50%"', 'image', 'sunscreen.jpg'],
    ['name like "Hand Sanitizer 500ml%"', 'image', 'hand_sanitizer.jpg'],
    
    // Groceries (Replacing generic placeholders)
    ['name like "%Maize Meal%" OR name like "%Wheat Flour%"', 'image', 'flour.jpg'],
    ['name like "%Vegetable Oil%" OR name like "%Corn Oil%"', 'image', 'vegetable_oil.jpg'],
    ['name like "%Washing Powder%"', 'image', 'washing_powder.jpg'],
    ['name like "%Soap%"', 'image', 'soap.jpg'],
    ['name like "%Margarine%"', 'image', 'margarine.jpg'],
    ['name like "%Tea Bags%"', 'image', 'food.jpg'], // fallback
    ['name like "%Table Salt%"', 'image', 'food.jpg'], // fallback
    ['name like "%Tomato Ketchup%"', 'image', 'ketchup.jpg'],
    ['name like "Weetabix Cereal%"', 'image', 'cereal.jpg']
];

foreach ($updates as $u) {
    $where = $u[0];
    $col = $u[1];
    $val = $u[2];
    
    // Escape string values
    $final_val = is_numeric($val) ? $val : "'$val'";
    
    $query = "UPDATE products SET $col = $final_val WHERE $where";
    $res = mysqli_query($con, $query);
    
    if ($res) {
        echo "Updated $where ($col -> $val)<br>";
    } else {
        echo "Failed to update $where<br>";
    }
}

echo "<br><b>Database Update Complete!</b>";
?>
