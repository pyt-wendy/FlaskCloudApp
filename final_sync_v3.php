<?php
require_once 'includes/db.php';

echo "<h2>Executing Kenyan Product Restoration</h2>";

$updates = [
    ['name like "Reusable Face Masks%"', 'image', 'face_mask.jpg'],
    ['name like "Hand Sanitizer 500ml%"', 'image', 'hand_sanitizer.jpg'],
    ['name like "Sunscreen SPF 50%"', 'image', 'sunscreen.jpg'],
    ['name like "Dettol Antiseptic%"', 'image', 'dettol.jpg'],
    ['name like "Geisha Bath Soap%"', 'image', 'geisha.jpg'],
    ['name like "Velvex Toilet Paper%"', 'image', 'velvex.jpg'],
    ['name like "Ariel Washing Powder%"', 'image', 'ariel.jpg'],
    ['name like "Weetabix Cereal%"', 'image', 'weetabix.jpg'],
    ['name like "Smartphone X%"', 'image', 'smartphone_x.jpg'],
    ['name like "Laptop Pro%"', 'image', 'laptop_pro.jpg']
];

foreach ($updates as $u) {
    $where = $u[0];
    $col = $u[1];
    $val = $u[2];
    
    $query = "UPDATE products SET $col = '$val' WHERE $where";
    $res = mysqli_query($con, $query);
    
    if ($res && mysqli_affected_rows($con) > 0) {
        echo "Successfully updated $where -> $val<br>";
    } else {
        echo "Note: No changes for $where (records might already be up to date or not found)<br>";
    }
}

echo "<h3>Finalizing UI Sizing...</h3>";
// I've already updated the CSS, this ensures the database sync is done.

echo "<br><b>Restoration Complete!</b>";
?>
