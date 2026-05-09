<?php
require_once 'includes/db.php';
$res = mysqli_query($con, "SELECT name, category FROM products");
while ($row = mysqli_fetch_assoc($res)) {
    echo $row['name'] . " | " . $row['category'] . PHP_EOL;
}
?>
