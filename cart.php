<?php
require('includes/header.php');

if (isset($_POST['add_to_cart'])) {
    $product_id = get_safe_value($con, $_POST['product_id']);
    $qty = get_safe_value($con, $_POST['qty']);

    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $qty;
    }
    else {
        $_SESSION['cart'][$product_id] = $qty;
    }
}

if (isset($_GET['type']) && $_GET['type'] == 'delete') {
    $product_id = get_safe_value($con, $_GET['id']);
    unset($_SESSION['cart'][$product_id]);
    redirect('cart.php');
}

if (isset($_POST['update_cart'])) {
    foreach ($_POST['qty'] as $key => $val) {
        if ($val > 0) {
            $_SESSION['cart'][$key] = $val;
        }
        else {
            unset($_SESSION['cart'][$key]);
        }
    }
    redirect('cart.php');
}
?>

<div class="container mt-4">
    <h2 class="mb-4">Your Shopping Cart</h2>
    <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) { ?>
        <form method="post">
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Store</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
    $grand_total = 0;
    foreach ($_SESSION['cart'] as $id => $qty) {
        $res = mysqli_query($con, "SELECT products.*, stores.name as store_name FROM products JOIN stores ON products.store_id = stores.id WHERE products.id='$id'");
        $row = mysqli_fetch_assoc($res);
        $total = $row['price'] * $qty;
        $grand_total += $total;
?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="uploads/<?php echo $row['image']; ?>" class="rounded me-3" width="50" height="50" style="object-fit: cover;">
                                        <span><?php echo $row['name']; ?></span>
                                    </div>
                                </td>
                                <td><span class="badge bg-secondary"><?php echo $row['store_name']; ?></span></td>
                                <td>Ksh <?php echo $row['price']; ?></td>
                                <td><input type="number" name="qty[<?php echo $id; ?>]" value="<?php echo $qty; ?>" min="1" max="<?php echo $row['stock']; ?>" class="form-control" style="width: 80px;"></td>
                                <td>Ksh <?php echo $total; ?></td>
                                <td><a href="?type=delete&id=<?php echo $id; ?>" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></a></td>
                            </tr>
                            <?php
    }?>
                            <tr>
                                <td colspan="4" class="text-end fs-5"><strong>Grand Total</strong></td>
                                <td colspan="2" class="fs-5 text-success"><strong>Ksh <?php echo $grand_total; ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="d-flex justify-content-between mt-4">
                <a href="stores.php" class="btn btn-outline-secondary rounded-pill px-4">Continue Shopping</a>
                <div>
                    <button type="submit" name="update_cart" class="btn btn-info rounded-pill px-4 me-2 text-white">Update Cart</button>
                    <a href="checkout.php" class="btn btn-glovo rounded-pill px-5">Checkout</a>
                </div>
            </div>
        </form>
    <?php
}
else { ?>
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <p class="lead">Your cart is empty.</p>
            <a href="stores.php" class="btn btn-glovo rounded-pill px-4">Start Shopping</a>
        </div>
    <?php
}?>
</div>

<?php require('includes/footer.php'); ?>
