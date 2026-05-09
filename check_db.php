<?php
require_once 'includes/db.php';

echo "<h2>Database Diagnostic</h2>";

$stores_count = mysqli_query($con, "SELECT COUNT(*) as count FROM stores");
$s_count = mysqli_fetch_assoc($stores_count)['count'];
echo "Total Stores: " . $s_count . "<br>";

$products_count = mysqli_query($con, "SELECT COUNT(*) as count FROM products");
$p_count = mysqli_fetch_assoc($products_count)['count'];
echo "Total Products: " . $p_count . "<br>";

if ($s_count > 0) {
    echo "<h3>Stores List:</h3>";
    $stores = mysqli_query($con, "SELECT name, category FROM stores");
    while ($s = mysqli_fetch_assoc($stores)) {
        echo "- " . $s['name'] . " (" . $s['category'] . ")<br>";
    }
}
else {
    echo "<p style='color:red;'>No stores found in database!</p>";
}

if ($p_count > 0) {
    echo "<h3>Sample Products:</h3>";
    $products = mysqli_query($con, "SELECT name FROM products LIMIT 5");
    while ($p = mysqli_fetch_assoc($products)) {
        echo "- " . $p['name'] . "<br>";
    }
}
else {
    echo "<p style='color:red;'>No products found in database!</p>";
}
?>
