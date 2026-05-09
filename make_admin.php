<?php
require('includes/db.php');
$username = 'Admin';
$email = 'admin@onlineshop.com';
$password = 'admin123';
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$check = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
if (mysqli_num_rows($check) > 0) {
    mysqli_query($con, "UPDATE users SET role='admin', password='$password_hash' WHERE email='$email'");
    echo "Admin user updated successfully. You can login with:\nEmail: $email\nPassword: $password\n";
} else {
    mysqli_query($con, "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password_hash', 'admin')");
    echo "Admin user created successfully. You can login with:\nEmail: $email\nPassword: $password\n";
}
?>
