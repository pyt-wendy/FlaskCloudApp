<?php
require('includes/header.php');

// "Just for You" logic: Fetch products from session cart or previous orders
$just_for_you_products = [];
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
if (count($just_for_you_products) < 15 && isset($_SESSION['USER_ID'])) {
    $user_id = $_SESSION['USER_ID'];
    $exclude_ids = !empty($just_for_you_products) ? implode(',', array_column($just_for_you_products, 'id')) : '0';
    $res = mysqli_query($con, "SELECT DISTINCT p.* FROM products p JOIN order_items oi ON p.id = oi.product_id JOIN orders o ON oi.order_id = o.id WHERE o.user_id = '$user_id' AND p.id NOT IN ($exclude_ids) LIMIT 15");
    while ($row = mysqli_fetch_assoc($res)) {
        if (count($just_for_you_products) >= 15)
            break;
        $just_for_you_products[] = $row;
    }
}
if (count($just_for_you_products) < 15) {
    $needed = 15 - count($just_for_you_products);
    $res = mysqli_query($con, "SELECT * FROM products WHERE stock > 0 ORDER BY RAND() LIMIT $needed");
    while ($row = mysqli_fetch_assoc($res)) {
        $just_for_you_products[] = $row;
    }
}

$cat_filter = isset($_GET['cat']) ? $_GET['cat'] : '';

// Get categories to group by
if ($cat_filter != '') {
    $categories = [$cat_filter];
}
else {
    $cat_res = mysqli_query($con, "SELECT DISTINCT category FROM stores WHERE category != 'unclassified' ORDER BY category ASC");
    $categories = [];
    while ($c_row = mysqli_fetch_assoc($cat_res)) {
        $categories[] = $c_row['category'];
    }
}

$cat_colors = [
    'groceries' => '#E8F5E9',
    'supermarket' => '#E3F2FD',
    'pharmacy' => '#FFEBEE',
    'drinks' => '#F3E5F5',
    'fast food' => '#FFF3E0',
    'specialty' => '#F3E5F5'
];
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

        <!-- Main Content: Grouped Stores -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-uppercase tracking-wider m-0">Our Stores</h2>
                <?php if ($cat_filter != '') { ?>
                    <a href="stores.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">View All Categories</a>
                <?php
}?>
            </div>

            <?php foreach ($categories as $cat) {
    $stores_res = get_stores($con, $cat);
    if (mysqli_num_rows($stores_res) > 0) {
        $bg_color = isset($cat_colors[strtolower($cat)]) ? $cat_colors[strtolower($cat)] : '#F5F5F5';
?>
                <div class="mb-5">
                    <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded-4 shadow-sm" style="background: <?php echo $bg_color; ?>;">
                        <div class="p-2 bg-white rounded-circle shadow-sm" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-store text-dark small"></i>
                        </div>
                        <h5 class="fw-bold m-0 text-uppercase tracking-widest small"><?php echo ucfirst($cat); ?></h5>
                    </div>

                    <div class="row g-4">
                        <?php while ($row = mysqli_fetch_assoc($stores_res)) {
            // Proactive Data Fix
            $s_name = strtolower($row['name']);
            $s_cat = strtolower($row['category']);

            // Strict Filter: Allow all stores to show
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
            if (strtolower($row['category']) == 'supermarket' && ($row['image'] == 'default_store.jpg' || $row['image'] == '')) {
                $row['image'] = 'supermarket.jpg';
            }
            if (strtolower($row['category']) == 'pharmacy' && ($row['image'] == 'default_store.jpg' || $row['image'] == '')) {
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
                            <div class="col-md-4 col-6">
                                <div class="card store-card h-100 shadow-sm border-0 rounded-4 overflow-hidden transition-all hover-lift">
                                    <div class="position-relative">
                                        <img src="uploads/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['name']; ?>" style="height: 140px; object-fit: cover;">
                                        <div class="position-absolute bottom-0 start-0 w-100 p-2 bg-dark bg-opacity-10 backdrop-blur">
                                            <span class="badge bg-white text-dark rounded-pill shadow-sm small">
                                                <i class="fas fa-clock me-1 text-success"></i> <?php echo $row['opening_hours']; ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <h6 class="card-title fw-bold small mb-1"><?php echo $row['name']; ?></h6>
                                        <p class="text-muted micro-text mb-2"><?php echo substr($row['description'], 0, 50); ?>...</p>
                                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top">
                                            <span class="text-warning small"><i class="fas fa-star me-1"></i> 4.8</span>
                                            <i class="fas fa-arrow-right text-primary micro-text"></i>
                                        </div>
                                        <a href="store_products.php?id=<?php echo $row['id']; ?>" class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>
                        <?php
        }?>
                    </div>
                </div>
            <?php
    }
}

if (empty($categories)) { ?>
                <div class="text-center py-5">
                    <i class="fas fa-store-slash fa-3x text-muted mb-3"></i>
                    <p class="lead">No stores available yet.</p>
                </div>
            <?php
}?>
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
.tracking-widest {
    letter-spacing: 2px;
}
.backdrop-blur {
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}
</style>

<?php require('includes/footer.php'); ?>
