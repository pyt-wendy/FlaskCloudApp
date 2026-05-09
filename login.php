<?php
$hide_location_bar = true;
require('includes/header.php');
$msg = '';
if (isset($_POST['submit'])) {
    $email = get_safe_value($con, $_POST['email']);
    $password = get_safe_value($con, $_POST['password']);
    $res = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        if (password_verify($password, $row['password'])) {
            $_SESSION['USER_ID'] = $row['id'];
            $_SESSION['USER_NAME'] = $row['username'];
            $_SESSION['USER_ROLE'] = $row['role'];
            header('location:index.php');
            die();
        }
        else {
            $msg = "Please enter the correct login details";
        }
    }
    else {
        $msg = "Please enter the correct login details";
    }
}
?>
<div class="auth-wrapper-simple">
    <!-- Side Illustrations -->
    <div class="illustration-side illustration-left d-none d-lg-flex">
        <i class="fas fa-shopping-bag illustration-3d-auth"></i>
    </div>
    <div class="illustration-side illustration-right d-none d-lg-flex">
        <i class="fas fa-shopping-cart illustration-3d-auth"></i>
    </div>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <!-- LOGIN CENTER -->
            <div class="col-md-6 col-lg-5">
                <div class="card w-100 shadow border-0 rounded-4">
                    <div class="card-header text-center bg-white border-0 pt-4">
                        <h2 class="fw-bold">Login</h2>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <form method="post">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Email address</label>
                                <input type="email" name="email" class="form-control rounded-pill px-3" placeholder="Enter your email" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Password</label>
                                <input type="password" name="password" class="form-control rounded-pill px-3" placeholder="Enter your password" required>
                            </div>
                            <div class="d-grid shadow-sm rounded-pill">
                                <button type="submit" name="submit" class="btn btn-glovo py-2 rounded-pill fw-bold">
                                    Login
                                </button>
                            </div>
                            <div class="text-danger mt-3 text-center fw-bold small">
                                <?php echo $msg; ?>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center bg-white border-0 pb-4">
                        Don't have an account? <a href="register.php" class="text-primary text-decoration-none fw-bold">Register here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('includes/footer.php'); ?>
