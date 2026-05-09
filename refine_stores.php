<?php
require_once 'includes/db.php';

echo "<h2>Store Refinement & Opening Hours</h2>";

// 1. Add opening_hours column if it doesn't exist
$check_col = mysqli_query($con, "SHOW COLUMNS FROM `stores` LIKE 'opening_hours'");
if (mysqli_num_rows($check_col) == 0) {
    mysqli_query($con, "ALTER TABLE `stores` ADD `opening_hours` VARCHAR(50) DEFAULT '9 AM - 9 PM' AFTER `image` text");
    echo "Added 'opening_hours' column to stores table.<br>";
}

// 2. Delete all supermarkets except Chandarana
mysqli_query($con, "DELETE FROM stores WHERE category = 'Supermarket' AND name != 'Chandarana'");
echo "Kept only Chandarana in Supermarket category.<br>";

// 3. Update Specific Store Hours
$hours = [
    'Chandarana' => 'Open until 10 PM',
    'Goodlife' => 'Open 24/7',
    'KFC' => 'Open until Midnight',
    'Galitos' => 'Open until Midnight',
    'Pizza Planet' => 'Open until 11 PM',
    'Burger King' => 'Open until 11 PM',
    'Drinks 24/7' => 'Open 24/7',
    'The Bakers' => 'Open until 9 PM',
    'Roast Chicken' => 'Open until 10 PM',
    'Pet Store' => 'Open until 8 PM'
];

foreach ($hours as $name => $time) {
    mysqli_query($con, "UPDATE stores SET opening_hours = '$time' WHERE name = '$name'");
    echo "Set $name hours to: $time<br>";
}

echo "<br><b>Refinement complete!</b> Visit your site to see the new times.";
?>
