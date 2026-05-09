<?php
$hide_location_bar = true;
require('includes/header.php');

$msg = '';
if (isset($_POST['submit'])) {
    $username = get_safe_value($con, $_POST['username']);
    $email = get_safe_value($con, $_POST['email']);
    $password = get_safe_value($con, $_POST['password']);

    $check_user = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check_user) > 0) {
        $msg = "Email already present";
    }
    else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        mysqli_query($con, "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password_hash', 'customer')");
?>
        <script>
            alert('Registration Successful');
            window.location.href = 'login.php';
        </script>
        <?php
    }
}
?>

<div class="auth-wrapper-simple">
  
    <div class="illustration-side illustration-left d-none d-lg-flex">
        <i class="fas fa-gift illustration-3d-auth"></i>
    </div>
    <div class="illustration-side illustration-right d-none d-lg-flex">
        <i class="fas fa-box-open illustration-3d-auth"></i>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow border-0 rounded-4">
                <div class="card-header text-center bg-white border-0 pt-4">
                    <h2 class="fw-bold">Register</h2>
                </div>
                <div class="card-body px-4 pb-4">
                    <form method="post">
                        <div class="mb-4">
                            <label for="username" class="form-label fw-bold">Username</label>
                            <input type="text" name="username" class="form-control rounded-pill px-3" id="username" placeholder="Choose a username" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Email address</label>
                            <input type="email" name="email" class="form-control rounded-pill px-3" id="email" placeholder="Your email address" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control rounded-pill px-3" id="password" placeholder="Create a password" required>
                        </div>
                        <div class="d-grid shadow-sm rounded-pill">
                            <button type="submit" name="submit" class="btn btn-glovo py-2 rounded-pill fw-bold">Register</button>
                        </div>
                        <div class="text-danger mt-3 text-center fw-bold small"><?php echo $msg; ?></div>
                    </form>
                </div>
                <div class="card-footer text-center bg-white border-0 pb-4">
                    Already have an account? <a href="login.php" class="text-primary text-decoration-none fw-bold">Login here</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('includes/footer.php'); ?>
