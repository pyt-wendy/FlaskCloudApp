<?php
require('includes/header.php');

if (!check_login($con)) {
    redirect('login.php');
}

$user_id = $_SESSION['USER_ID'];
$res = mysqli_query($con, "SELECT * FROM orders WHERE user_id='$user_id' ORDER BY id DESC");
?>

<div class="container mt-4">
    <h2>My Orders</h2>
    <div class="card">
        <div class="card-body">
            <?php if (mysqli_num_rows($res) > 0) { ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($res)) { ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td>Ksh <?php echo $row['total_amount']; ?></td>
                        <td><?php echo $row['payment_method']; ?></td>
                        <td>
                             <span class="badge bg-<?php echo($row['status'] == 'completed') ? 'success' : (($row['status'] == 'cancelled') ? 'danger' : 'warning'); ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php
    }?>
                </tbody>
            </table>
            <?php
}
else { ?>
                <p>No orders found.</p>
            <?php
}?>
        </div>
    </div>
</div>

<?php require('includes/footer.php'); ?>
