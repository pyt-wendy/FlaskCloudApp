<?php
require_once 'includes/db.php';
$res = mysqli_query($con, "SELECT COUNT(*) as count FROM stores");
echo "Store Count: " . mysqli_fetch_assoc($res)['count'] . PHP_EOL;

$res_p = mysqli_query($con, "SELECT COUNT(*) as count FROM products");
echo "Product Count: " . mysqli_fetch_assoc($res_p)['count'] . PHP_EOL;

$res_s = mysqli_query($con, "SELECT id, name, category FROM stores");
while ($row = mysqli_fetch_assoc($res_s)) {
    echo "ID: " . $row['id'] . " | Name: " . $row['name'] . " | Category: " . $row['category'] . PHP_EOL;
}
?>
