<?php
require('includes/header.php');

$products_res = mysqli_query($con, "SELECT * FROM products WHERE stock > 0 ORDER BY id DESC");
?>

<div class="container py-4">
    <!-- Vibrant Header -->
    <div class="rounded-4 p-4 mb-5 d-flex align-items-center justify-content-between shadow-sm border border-light" style="background-color: #E8F5E9;">
        <div class="d-flex align-items-center gap-4">
            <div class="cat-icon-mini bg-white shadow-sm" style="width: 70px; height: 70px; font-size: 2rem; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-boxes" style="color: rgba(0,0,0,0.6);"></i>
            </div>
            <div>
                <h1 class="fw-bold text-uppercase tracking-wider m-0" style="color: #1a1a1a; font-size: 1.8rem;">
                    All Products
                </h1>
                <p class="text-muted m-0 small">Fresh arrivals and popular deals</p>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <h5 class="fw-bold m-0 text-uppercase tracking-wider small text-muted">Available Now</h5>
    </div>

    <!-- Product Grid for All Products -->
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-4 mb-5">
        <?php
        if (mysqli_num_rows($products_res) > 0) {
            while ($row = mysqli_fetch_assoc($products_res)) {
        ?>
            <div class="col">
                <div class="compact-product-card h-100">
                    <div class="compact-p-img-wrapper" style="height: 180px;">
                        <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>" style="object-fit: contain; width: 100%; height: 100%;">
                        <div class="position-absolute top-0 end-0 p-2">
                            <form action="cart.php" method="POST" class="m-0">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <input type="hidden" name="qty" value="1">
                                <button type="submit" name="add_to_cart" class="btn-add-mini" title="Add to cart">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="compact-p-info">
                        <div class="compact-p-name text-truncate"><?php echo $row['name']; ?></div>
                        <div class="compact-p-price">Ksh <?php echo $row['price']; ?></div>
                    </div>
                    <a href="product.php?id=<?php echo $row['id']; ?>" class="stretched-link"></a>
                </div>
            </div>
        <?php
            }
        } else {
        ?>
            <div class="col-12 text-center py-5 w-100">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <p class="lead">No products found.</p>
                <a href="index.php" class="btn btn-glovo rounded-pill px-4">Go Home</a>
            </div>
        <?php
        } ?>
    </div>
</div>

<?php require('includes/footer.php'); ?>
