<?php
require('includes/header.php');

if (!check_login($con)) {
    redirect('login.php');
}

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    redirect('index.php');
}

if (isset($_POST['submit'])) {
    $address = get_safe_value($con, $_POST['address']);
    $payment_method = get_safe_value($con, $_POST['payment_method']);
    $user_id = $_SESSION['USER_ID'];
    $total_amount = 0;

    foreach ($_SESSION['cart'] as $id => $qty) {
        $res = mysqli_query($con, "SELECT price FROM products WHERE id='$id'");
        $row = mysqli_fetch_assoc($res);
        $total_amount += $row['price'] * $qty;
    }

    // Insert Order
    mysqli_query($con, "INSERT INTO orders (user_id, total_amount, status, payment_method, shipping_address) VALUES ('$user_id', '$total_amount', 'pending', '$payment_method', '$address')");
    $order_id = mysqli_insert_id($con);

    // Insert Order Items
    foreach ($_SESSION['cart'] as $id => $qty) {
        $res = mysqli_query($con, "SELECT price FROM products WHERE id='$id'");
        $row = mysqli_fetch_assoc($res);
        $price = $row['price'];
        mysqli_query($con, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ('$order_id', '$id', '$qty', '$price')");
    }

    unset($_SESSION['cart']);

    if ($payment_method == 'M-Pesa') {
?>
    <script>
        window.location.href='payment.php?order_id=<?php echo $order_id; ?>';
    </script>
<?php
    }
    else {
?>
    <script>
        alert('Order Placed Successfully');
        window.location.href='orders.php';
    </script>
<?php
    }
}
?>

<div class="container mt-4">
    <h2 class="mb-4">Checkout</h2>
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Shipping Details</h5>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="mb-3">
                            <label for="address" class="form-label">Delivery Address</label>
                            <textarea name="address" class="form-control" rows="3" required placeholder="Enter your full delivery address"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select name="payment_method" class="form-control">
                                <option value="M-Pesa">M-Pesa</option>
                                <option value="COD">Cash on Delivery</option>
                                <option value="Card">Credit/Debit Card</option>
                            </select>
                        </div>
                        <button type="submit" name="submit" class="btn btn-glovo btn-lg w-100 rounded-pill">Place Order</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                     <?php
$grand_total = 0;
foreach ($_SESSION['cart'] as $id => $qty) {
    $res = mysqli_query($con, "SELECT name, price, store_id FROM products WHERE id='$id'");
    $row = mysqli_fetch_assoc($res);

    // Get Store Name
    $store_name = get_store_name($con, $row['store_id']);

    $total = $row['price'] * $qty;
    $grand_total += $total;
?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <span class="fw-bold"><?php echo $row['name']; ?></span> <span class="text-muted">x<?php echo $qty; ?></span>
                            <br><small class="text-muted">From: <?php echo $store_name; ?></small>
                        </div>
                        <span>Ksh <?php echo $total; ?></span>
                    </div>
                    <?php
}?>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Total</h5>
                        <h4 class="text-success mb-0">Ksh <?php echo $grand_total; ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require('includes/footer.php'); ?>
