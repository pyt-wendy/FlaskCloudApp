<?php
require_once 'includes/db.php';

// Update Pet Store image
$sql = "UPDATE stores SET image = 'petstore.png' WHERE name = 'Pet Store'";
if (mysqli_query($con, $sql)) {
    echo "Pet Store image updated successfully!<br>";
}
else {
    echo "Error updating Pet Store image: " . mysqli_error($con) . "<br>";
}

echo "You can now delete this file.";
?>
