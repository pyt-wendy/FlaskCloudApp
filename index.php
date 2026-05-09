<?php
require('includes/header.php');

$stores_res = mysqli_query($con, "SELECT * FROM stores ORDER BY id DESC LIMIT 8");
$products_res = mysqli_query($con, "SELECT * FROM products WHERE stock > 0 ORDER BY id DESC LIMIT 16");
?>

<div class="container py-4">
    <!-- Modern Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="hero-banner rounded-4 shadow-lg overflow-hidden position-relative" style="height: 400px; background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('uploads/food.jpg') center/cover;">
                <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-75">
                    <h1 class="display-4 fw-bold mb-3">Freshness Delivered <br>To Your Doorstep</h1>
                    <p class="lead mb-4">Discover the best products from your favorite local stores.</p>
                    <a href="stores.php" class="btn btn-warning btn-lg rounded-pill px-5 fw-bold shadow">Shop Now</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Minimal Category Link Row -->
    <div class="d-flex justify-content-between align-items-center mb-4 overflow-auto py-2 gap-4 no-scrollbar">
        <?php
$quick_cats = [
    ['name' => 'Groceries', 'icon' => 'fa-shopping-basket', 'color' => '#E8F5E9', 'image' => 'uploads/grocery.jpg'],
    ['name' => 'Food', 'icon' => 'fa-hamburger', 'color' => '#FFFDE7', 'image' => 'uploads/food.jpg'],
    ['name' => 'Supermarket', 'icon' => 'fa-store', 'color' => '#E3F2FD', 'image' => 'uploads/supermarket.jpg'],
    ['name' => 'Pharmacy', 'icon' => 'fa-briefcase-medical', 'color' => '#FFEBEE', 'image' => 'uploads/pharmacy.jpg'],
    ['name' => 'Drinks', 'icon' => 'fa-glass-cheers', 'color' => '#F3E5F5', 'image' => 'uploads/drink.jpg'],
    ['name' => 'Pet Store', 'icon' => 'fa-dog', 'color' => '#E8EAF6', 'image' => 'uploads/petstore.png'],
    ['name' => 'Electronics', 'icon' => 'fa-laptop', 'color' => '#F1F8E9', 'image' => 'uploads/headphones.jpg']
];
foreach ($quick_cats as $cat) {
?>
        <a href="category_products.php?cat=<?php echo strtolower($cat['name']); ?>" class="cat-card-compact border-0 bg-transparent">
            <div class="cat-icon-mini shadow-sm" style="background-color: <?php echo $cat['color']; ?>; border: none; overflow: hidden;">
                <?php if (isset($cat['image'])) { ?>
                    <img src="<?php echo $cat['image']; ?>" alt="<?php echo $cat['name']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php
    }
    else { ?>
                    <i class="fas <?php echo $cat['icon']; ?>" style="color: rgba(0,0,0,0.6);"></i>
                <?php
    }?>
            </div>
            <span><?php echo $cat['name']; ?></span>
        </a>
        <?php
}?>
    </div>

    <div class="row mb-5">
        <div class="col-12 d-flex justify-content-between align-items-end mb-3">
            <h5 class="fw-bold m-0 text-uppercase tracking-wider">Top Stores</h5>
            <a href="stores.php" class="text-muted small text-decoration-none">View All <i class="fas fa-chevron-right ms-1"></i></a>
        </div>
        <?php while ($row = mysqli_fetch_assoc($stores_res)) {
    // Proactive Data Fix: Ensure naming and images are correct even if DB migration isn't run yet
    $s_name = strtolower($row['name']);
    $s_cat = strtolower($row['category']);

    // Strict Filter: Allow all supermarkets to show
    /* if ($s_cat == 'supermarket' && $s_name != 'chandarana') {
     continue;
     } */

    if ($s_name == 'chicken inn') {
        $row['name'] = 'Roast Chicken';
        $row['image'] = 'roast chicken.jpg';
    }
    elseif ($s_name == 'pizza planet') {
        $row['image'] = 'pizza.jpg';
    }
    elseif ($s_name == 'kfc') {
        $row['image'] = 'kfc.jpg';
    }
    elseif ($s_name == 'galitos') {
        $row['image'] = 'galitos.jpg';
    }
    elseif ($s_name == 'burger king') {
        $row['image'] = 'burger.jpg';
    }
    elseif ($s_name == 'the bakers') {
        $row['image'] = 'bakers inn.jpg';
    }
    elseif ($s_name == 'drinks 24/7') {
        $row['image'] = 'drinks soju.jpg';
    }

    if ($row['name'] == 'Pet Store' && ($row['image'] == 'default_store.jpg' || $row['image'] == '')) {
        $row['image'] = 'petstore.png';
    }
    if ($row['category'] == 'Supermarket' && ($row['image'] == 'default_store.jpg' || $row['image'] == '')) {
        $row['image'] = 'supermarket.jpg';
    }
    if ($row['category'] == 'Pharmacy' && ($row['image'] == 'default_store.jpg' || $row['image'] == '')) {
        $row['image'] = 'pharmacy.jpg';
    }

    // Proactive Data Fix: Store Hours
    if (empty($row['opening_hours'])) {
        if ($s_name == 'kfc' || $s_name == 'galitos')
            $row['opening_hours'] = 'Open until Midnight';
        elseif ($s_name == 'goodlife' || $s_name == 'drinks 24/7')
            $row['opening_hours'] = 'Open 24/7';
        elseif ($s_name == 'pizza planet' || $s_name == 'burger king')
            $row['opening_hours'] = 'Open until 11 PM';
        elseif ($s_name == 'chandarana' || $s_name == 'roast chicken')
            $row['opening_hours'] = 'Open until 10 PM';
        else
            $row['opening_hours'] = 'Open until 9 PM';
    }
?>
        <div class="col-md-3 col-6 mb-3">
            <div class="card store-card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                <img src="uploads/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>" style="height: 120px; object-fit: cover;">
                <div class="card-body p-3 text-center">
                    <h6 class="card-title fw-bold small m-0"><?php echo $row['name']; ?></h6>
                    <p class="text-muted extra-small m-0 mt-1"><?php echo $row['opening_hours']; ?></p>
                    <a href="store_products.php?id=<?php echo $row['id']; ?>" class="stretched-link"></a>
                </div>
            </div>
        </div>
        <?php
}?>
    </div>

    <!-- Mini Promo Banner Interlude -->
    <div class="promo-banner-mini shadow-sm">
        <div class="promo-content-mini">
            <h4 class="m-0 fw-bold">Exclusive Weekend Deals!</h4>
            <p class="m-0 small opacity-75">Up to 40% off on your first grocery order.</p>
        </div>
        <div class="d-none d-md-block">
            <a href="categories.php" class="btn btn-light btn-sm rounded-pill px-4 fw-bold shadow-sm">Claim Now</a>
        </div>
    </div>

    <div class="col-12 d-flex justify-content-between align-items-end mb-3">
        <h5 class="fw-bold m-0 text-uppercase tracking-wider">Fresh Arrivals</h5>
        <a href="products.php" class="text-muted small text-decoration-none">Explore <i class="fas fa-chevron-right ms-1"></i></a>
    </div>
    <div class="horizontal-scroll-row custom-scrollbar mb-5">
        <?php while ($row = mysqli_fetch_assoc($products_res)) { ?>
        <div class="compact-product-card" style="min-width: 220px; max-width: 220px; flex-shrink: 0;">
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
}?>
    </div>

</div>

<?php require('includes/footer.php'); ?>
