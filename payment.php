<?php
require('includes/header.php');

if (!check_login($con)) {
    redirect('login.php');
}

$order_id = get_safe_value($con, $_GET['order_id']);

if ($order_id == '') {
    redirect('index.php');
}

// Get Order Details
$res = mysqli_query($con, "SELECT * FROM orders WHERE id='$order_id' AND user_id='" . $_SESSION['USER_ID'] . "'");
if (mysqli_num_rows($res) == 0) {
    redirect('index.php');
}
$order_row = mysqli_fetch_assoc($res);

$simulation_step = 1;
if (isset($_POST['send_stk'])) {
    $phone = get_safe_value($con, $_POST['phone']);
    $simulation_step = 2;
}

if (isset($_POST['confirm_payment'])) {
    // Simulate Payment Success
    mysqli_query($con, "UPDATE orders SET status='completed' WHERE id='$order_id'");

    // Redirect to Orders page
?>
    <script>
        alert('Payment Successful! Order Completed.');
        window.location.href='orders.php';
    </script>
    <?php
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">M-Pesa Payment</h4>
                </div>
                <div class="card-body text-center p-5">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/15/M-PESA_LOGO-01.svg/1200px-M-PESA_LOGO-01.svg.png" alt="M-Pesa" class="mb-4" style="height: 80px;">
                    
                    <h3 class="fw-bold mb-4">KES <?php echo $order_row['total_amount']; ?></h3>

                    <?php if ($simulation_step == 1) { ?>
                        <!-- Step 1: Input Phone Number -->
                        <h5 class="mb-3">Enter M-Pesa Phone Number</h5>
                        <p class="text-muted">We will send a payment request to this phone.</p>
                        
                        <form method="post">
                            <div class="mb-3">
                                <input type="text" name="phone" class="form-control form-control-lg text-center" placeholder="0712 345 678" required>
                            </div>
                            <button type="submit" name="send_stk" class="btn btn-success btn-lg w-100 rounded-pill shadow">
                                <i class="fas fa-paper-plane me-2"></i> Send Request
                            </button>
                        </form>

                    <?php
}
else { ?>
                        <!-- Step 2: Simulate Waiting -->
                        <h5 class="mb-3">Check your phone</h5>
                        <p class="text-muted">Enter your M-Pesa PIN to complete payment.</p>
                        
                        <div class="d-flex justify-content-center my-4">
                            <div class="spinner-border text-success me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <span class="align-self-center">Waiting for payment...</span>
                        </div>

                        <form method="post">
                            <button type="submit" name="confirm_payment" class="btn btn-success btn-lg w-100 rounded-pill shadow">
                                <i class="fas fa-check-circle me-2"></i> I have Paid
                            </button>
                        </form>
                        
                        <p class="mt-3 text-muted small">Simulation: This button confirms the payment automatically.</p>
                    <?php
}?>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('includes/footer.php'); ?>
