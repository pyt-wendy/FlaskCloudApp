<?php
require('includes/header.php');

$cat = isset($_GET['cat']) ? $_GET['cat'] : '';
if ($cat == '') {
    redirect('index.php');
}

$products_res = get_products_by_category($con, $cat);
?>

<?php
$cat_meta = [
    'groceries' => ['color' => '#E8F5E9', 'icon' => 'fa-shopping-basket', 'image' => 'uploads/grocery.jpg'],
    'food' => ['color' => '#FFFDE7', 'icon' => 'fa-hamburger', 'image' => 'uploads/food.jpg'],
    'supermarket' => ['color' => '#E3F2FD', 'icon' => 'fa-store', 'image' => 'uploads/supermarket.jpg'],
    'pharmacy' => ['color' => '#FFEBEE', 'icon' => 'fa-briefcase-medical', 'image' => 'uploads/pharmacy.jpg'],
    'drinks' => ['color' => '#F3E5F5', 'icon' => 'fa-glass-cheers', 'image' => 'uploads/drink.jpg'],
    'electronics' => ['color' => '#F1F8E9', 'icon' => 'fa-laptop', 'image' => 'uploads/headphones.jpg'],
    'pizza' => ['color' => '#FFF3E0', 'icon' => 'fa-pizza-slice', 'image' => 'uploads/pizza.jpg'],
    'burgers' => ['color' => '#FBE9E7', 'icon' => 'fa-hamburger', 'image' => 'uploads/burger.jpg'],
    'roast chicken' => ['color' => '#FFF3E0', 'icon' => 'fa-drumstick-bite', 'image' => 'uploads/roast chicken.jpg'],
    'pet store' => ['color' => '#E8EAF6', 'icon' => 'fa-dog', 'image' => 'uploads/petstore.png'],
    'desserts' => ['color' => '#F3E5F5', 'icon' => 'fa-ice-cream']
];

$meta = isset($cat_meta[$cat]) ? $cat_meta[$cat] : ['color' => '#f8f9fa', 'icon' => 'fa-tag'];
?>

<div class="container py-4">
    <!-- Vibrant Category Header -->
    <div class="rounded-4 p-4 mb-5 d-flex align-items-center justify-content-between shadow-sm border border-light" style="background-color: <?php echo $meta['color']; ?>;">
        <div class="d-flex align-items-center gap-4">
            <div class="cat-icon-mini bg-white shadow-sm" style="width: 70px; height: 70px; font-size: 2rem; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                <?php if (isset($meta['image'])) { ?>
                    <img src="<?php echo $meta['image']; ?>" alt="<?php echo $cat; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php
}
else { ?>
                    <i class="fas <?php echo $meta['icon']; ?>" style="color: rgba(0,0,0,0.6);"></i>
                <?php
}?>
            </div>
            <div>
                <h1 class="fw-bold text-uppercase tracking-wider m-0" style="color: #1a1a1a; font-size: 1.8rem;">
                    <?php echo ucfirst($cat); ?>
                </h1>
                <p class="text-muted m-0 small">Freshly picked items just for you</p>
            </div>
        </div>
    </div>

    <div class="mb-4">
        <h5 class="fw-bold m-0 text-uppercase tracking-wider small text-muted">Available Products</h5>
    </div>

    <div class="horizontal-scroll-row custom-scrollbar pb-4">
        <?php
if (mysqli_num_rows($products_res) > 0) {
    while ($row = mysqli_fetch_assoc($products_res)) {
?>
            <div class="compact-product-card" style="min-width: 200px; max-width: 200px; flex-shrink: 0;">
                <div class="compact-p-img-wrapper">
                    <img src="uploads/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
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
                    <div class="compact-p-name"><?php echo $row['name']; ?></div>
                    <div class="compact-p-price">Ksh <?php echo $row['price']; ?></div>
                </div>
                <a href="product.php?id=<?php echo $row['id']; ?>" class="stretched-link"></a>
            </div>
        <?php
    }
}
else {
?>
            <div class="col-12 text-center py-5 w-100">
                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                <p class="lead">No products found in the <strong><?php echo $cat; ?></strong> category.</p>
                <a href="index.php" class="btn btn-glovo rounded-pill px-4">Continue Shopping</a>
            </div>
        <?php
}?>
    </div>
</div>

<?php require('includes/footer.php'); ?>
