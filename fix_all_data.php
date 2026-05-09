<?php
require_once 'includes/db.php';

echo "<h2>Comprehensive Data Fix - Exhaustive Version</h2>";

// 1. Rename Chicken Inn to Roast Chicken
$sql1 = "UPDATE stores SET name = 'Roast Chicken', image = 'roast chicken.jpg' WHERE name = 'Chicken Inn'";
mysqli_query($con, $sql1);
echo "1. Roast Chicken image updated.<br>";

// 2. Update Pet Store
$sql2 = "UPDATE stores SET image = 'petstore.png' WHERE name = 'Pet Store'";
mysqli_query($con, $sql2);
echo "2. Pet Store image updated.<br>";

// 3. Update Drinks 24/7
$sql3 = "UPDATE stores SET image = 'drinks soju.jpg' WHERE name = 'Drinks 24/7'";
mysqli_query($con, $sql3);
echo "3. Drinks 24/7 (Soju) image updated.<br>";

// 4. Update Fast Food Stores
$fast_foods = [
    'Pizza Planet' => 'pizza.jpg',
    'KFC' => 'kfc.jpg',
    'Galitos' => 'galitos.jpg',
    'Burger King' => 'burger.jpg',
    'The Bakers' => 'bakers inn.jpg'
];
foreach ($fast_foods as $name => $img) {
    mysqli_query($con, "UPDATE stores SET image = '$img' WHERE name = '$name'");
    echo "Updating $name -> $img... Done.<br>";
}

// 5. Update Categories
mysqli_query($con, "UPDATE stores SET image = 'supermarket.jpg' WHERE category = 'Supermarket'");
mysqli_query($con, "UPDATE stores SET image = 'pharmacy.jpg' WHERE category = 'Pharmacy'");
echo "4. Supermarket and Pharmacy categories standardized.<br>";

echo "<br><b>Data fix complete!</b> Please visit your site to see the results.";
?>
