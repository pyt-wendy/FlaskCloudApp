<?php
require('includes/header.php');

// "Just for You" logic: Fetch products from session cart or previous orders
$just_for_you_products = [];

// 1. Try Session Cart
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    $cart_ids = array_keys($_SESSION['cart']);
    $ids_str = implode(',', array_map('intval', $cart_ids));
    $res = mysqli_query($con, "SELECT * FROM products WHERE id IN ($ids_str) LIMIT 15");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            $just_for_you_products[] = $row;
        }
    }
}

// 2. Try Order History if logged in and still need more items
if (count($just_for_you_products) < 15 && isset($_SESSION['USER_ID'])) {
    $user_id = $_SESSION['USER_ID'];
    $exclude_ids = !empty($just_for_you_products) ? implode(',', array_column($just_for_you_products, 'id')) : '0';
    $res = mysqli_query($con, "SELECT DISTINCT p.* FROM products p JOIN order_items oi ON p.id = oi.product_id JOIN orders o ON oi.order_id = o.id WHERE o.user_id = '$user_id' AND p.id NOT IN ($exclude_ids) LIMIT 15");
    if ($res) {
        while ($row = mysqli_fetch_assoc($res)) {
            if (count($just_for_you_products) >= 15)
                break;
            $just_for_you_products[] = $row;
        }
    }
}

// Fallback if no specific user items
if (count($just_for_you_products) < 15) {
    $needed = 15 - count($just_for_you_products);
    $res = mysqli_query($con, "SELECT * FROM products WHERE stock > 0 ORDER BY RAND() LIMIT $needed");
    while ($row = mysqli_fetch_assoc($res)) {
        $just_for_you_products[] = $row;
    }
}
?>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar: Just for You -->
        <div class="col-lg-3">
            <div class="jfy-sidebar shadow-sm">
                <div class="jfy-sidebar-title text-uppercase small tracking-wider">
                    <i class="fas fa-magic text-warning"></i>
                    <span>Just for You</span>
                </div>
                <div class="jfy-list">
                    <?php if (empty($just_for_you_products)) { ?>
                        <p class="text-muted small text-center py-4">No recommendations yet.</p>
                    <?php
}
else { ?>
                        <?php foreach ($just_for_you_products as $row) { ?>
                        <div class="jfy-item-link mb-2">
                            <a href="product.php?id=<?php echo $row['id']; ?>" class="d-block p-2 rounded text-decoration-none text-dark hover-bg-light border-bottom">
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="small fw-600"><?php echo $row['name']; ?></span>
                                    <i class="fas fa-chevron-right micro-text text-muted"></i>
                                </div>
                            </a>
                        </div>
                        <?php
    }?>
                    <?php
}?>
                </div>
            </div>
        </div>

        <!-- Main Content: Category Discovery -->
        <div class="col-lg-9">
            <div class="text-center mb-5">
                <h2 class="fw-bold text-uppercase tracking-wider mb-2" style="color: #1a1a1a;">Discover Categories</h2>
                <p class="text-muted">Find everything you need in one place</p>
            </div>

            <!-- Redesigned Category Cards -->
            <div class="row g-3 mb-5">
                <?php
$main_cats = [
    ['name' => 'Groceries', 'icon' => 'fa-shopping-basket', 'color' => '#E8F5E9', 'link' => 'category_products.php?cat=groceries', 'image' => 'uploads/grocery.jpg'],
    ['name' => 'Food', 'icon' => 'fa-hamburger', 'color' => '#FFFDE7', 'link' => '#food-grid', 'image' => 'uploads/food.jpg'],
    ['name' => 'Supermarket', 'icon' => 'fa-store', 'color' => '#E3F2FD', 'link' => 'category_products.php?cat=supermarket', 'image' => 'uploads/supermarket.jpg'],
    ['name' => 'Pharmacy', 'icon' => 'fa-briefcase-medical', 'color' => '#FFEBEE', 'link' => 'category_products.php?cat=pharmacy', 'image' => 'uploads/pharmacy.jpg']
];
foreach ($main_cats as $cat) {
?>
                <div class="col-md-3 col-6">
                    <a href="<?php echo $cat['link']; ?>" class="card border-0 shadow-sm text-center p-3 h-100 transition-all hover-lift rounded-4 text-decoration-none">
                        <div class="cat-icon-lg mb-2 mx-auto" style="background-color: <?php echo $cat['color']; ?>; width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; overflow: hidden;">
                            <?php if (isset($cat['image'])) { ?>
                                <img src="<?php echo $cat['image']; ?>" alt="<?php echo $cat['name']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php
    }
    else { ?>
                                <i class="fas <?php echo $cat['icon']; ?>" style="color: rgba(0,0,0,0.6);"></i>
                            <?php
    }?>
                        </div>
                        <h6 class="fw-bold m-0 text-dark small"><?php echo $cat['name']; ?></h6>
                    </a>
                </div>
                <?php
}?>
            </div>

            <!-- Specialty Grid -->
            <div id="food-grid" class="row g-3">
                <h5 class="fw-bold mt-2 mb-3 text-uppercase small tracking-widest text-muted">Specialty Categories</h5>
                <?php
$specialty = [
    ['name' => 'Roast Chicken', 'icon' => 'fa-drumstick-bite', 'color' => '#FFF3E0', 'image' => 'uploads/roast chicken.jpg'],
    ['name' => 'Pizza', 'icon' => 'fa-pizza-slice', 'color' => '#FBE9E7'],
    ['name' => 'Drinks', 'icon' => 'fa-glass-whiskey', 'color' => '#E1F5FE', 'image' => 'uploads/drink.jpg'],
    ['name' => 'Pet Store', 'icon' => 'fa-dog', 'color' => '#E8EAF6', 'image' => 'uploads/petstore.png'],
    ['name' => 'Electronics', 'icon' => 'fa-laptop', 'color' => '#F3E5F5']
];
foreach ($specialty as $s) {
?>
                <div class="col-md-6">
                    <a href="category_products.php?cat=<?php echo strtolower($s['name']); ?>" class="cat-card-compact p-3 rounded-4 bg-white shadow-sm d-flex align-items-center gap-3 border border-light">
                        <div class="cat-icon-mini" style="background-color: <?php echo $s['color']; ?>; margin: 0; width: 40px; height: 40px; font-size: 0.9rem; overflow: hidden;">
                            <?php if (isset($s['image'])) { ?>
                                <img src="<?php echo $s['image']; ?>" alt="<?php echo $s['name']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php
    }
    else { ?>
                                <i class="fas <?php echo $s['icon']; ?>"></i>
                            <?php
    }?>
                        </div>
                        <span class="m-0 text-start small fw-bold"><?php echo $s['name']; ?></span>
                    </a>
                </div>
                <?php
}?>
            </div>
        </div>
    </div>
</div>

<style>
.hover-lift:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
}
.transition-all {
    transition: all 0.3s ease;
}
.tracking-wider {
    letter-spacing: 1.5px;
}
</style>

<?php require('includes/footer.php'); ?>
