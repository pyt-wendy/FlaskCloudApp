<?php
require('includes/header.php');

$store_id = get_safe_value($con, $_GET['id']);
if ($store_id == '') {
    redirect('stores.php');
}

$store_res = mysqli_query($con, "SELECT * FROM stores WHERE id='$store_id'");
$store_row = mysqli_fetch_assoc($store_res);

if ($store_row) {
    // Proactive Data Fix: Ensure naming and images are correct even if DB migration isn't run yet
    $s_name = strtolower($store_row['name']);
    $s_cat = strtolower($store_row['category']);

    // Proactive Data Fix: Store Hours
    if (empty($store_row['opening_hours'])) {
        if ($s_name == 'kfc' || $s_name == 'galitos')
            $store_row['opening_hours'] = 'Open until Midnight';
        elseif ($s_name == 'goodlife' || $s_name == 'drinks 24/7')
            $store_row['opening_hours'] = 'Open 24/7';
        elseif ($s_name == 'pizza planet' || $s_name == 'burger king')
            $store_row['opening_hours'] = 'Open until 11 PM';
        elseif ($s_name == 'chandarana' || $s_name == 'roast chicken')
            $store_row['opening_hours'] = 'Open until 10 PM';
        else
            $store_row['opening_hours'] = 'Open until 9 PM';
    }

    if ($s_name == 'chicken inn') {
        $store_row['name'] = 'Roast Chicken';
        $store_row['image'] = 'roast chicken.jpg';
    }
    elseif ($s_name == 'pizza planet') {
        $store_row['image'] = 'pizza.jpg';
    }
    elseif ($s_name == 'kfc') {
        $store_row['image'] = 'kfc.jpg';
    }
    elseif ($s_name == 'galitos') {
        $store_row['image'] = 'galitos.jpg';
    }
    elseif ($s_name == 'burger king') {
        $store_row['image'] = 'burger.jpg';
    }
    elseif ($s_name == 'the bakers') {
        $store_row['image'] = 'bakers inn.jpg';
    }
    elseif ($s_name == 'drinks 24/7') {
        $store_row['image'] = 'drinks soju.jpg';
    }
    elseif ($s_name == 'pet store') {
        $store_row['image'] = 'petstore.png';
    }

    if ($s_cat == 'supermarket' && ($store_row['image'] == 'default_store.jpg' || $store_row['image'] == '')) {
        $store_row['image'] = 'supermarket.jpg';
    }
    if ($s_cat == 'pharmacy' && ($store_row['image'] == 'default_store.jpg' || $store_row['image'] == '')) {
        $store_row['image'] = 'pharmacy.jpg';
    }
}

// Filter Logic
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
if ($filter == 'promotions') {
    $sql = "SELECT * FROM products WHERE store_id='$store_id' AND stock > 0 AND old_price IS NOT NULL ORDER BY id DESC";
}
else {
    $sql = "SELECT * FROM products WHERE store_id='$store_id' AND stock > 0 ORDER BY id DESC";
}
$products_res = mysqli_query($con, $sql);
?>

<div class="container mt-4">
    <div class="row">
        <!-- Left Column: Product List -->
        <div class="col-md-5">
            <h4 class="fw-bold mb-4">Products</h4>
            <div class="horizontal-product-list">
                <?php if (mysqli_num_rows($products_res) > 0) {
    while ($row = mysqli_fetch_assoc($products_res)) {
        $discount = 0;
        if ($row['old_price'] > 0) {
            $discount = round((($row['old_price'] - $row['price']) / $row['old_price']) * 100);
        }
?>
                <div class="product-card-horizontal">
                    <img src="uploads/<?php echo $row['image']; ?>" class="card-img-left" alt="<?php echo $row['name']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                        <div class="price-container">
                            <?php if ($discount > 0) { ?>
                                <span class="old-price small">Ksh <?php echo $row['old_price']; ?></span>
                            <?php
        }?>
                            <span class="new-price">Ksh <?php echo $row['price']; ?></span>
                        </div>
                        <a href="product.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-success btn-add rounded-pill">View</a>
                    </div>
                </div>
                <?php
    }
}
else { ?>
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-2x text-muted mb-3"></i>
                    <p>No products found.</p>
                </div>
                <?php
}?>
            </div>
        </div>

        <!-- Right Column: Store Info -->
        <div class="col-md-7">
            <div class="p-5 mb-4 bg-light rounded-4 text-center shadow-sm" style="background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('uploads/<?php echo $store_row['image']; ?>'); background-size: cover; background-position: center; height: 320px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                <div class="text-white">
                    <h1 class="display-5 fw-bold mb-2"><?php echo $store_row['name']; ?></h1>
                    <p class="fs-5 opacity-90 mb-3"><?php echo $store_row['description']; ?></p>
                    <div class="d-inline-flex align-items-center bg-white bg-opacity-20 backdrop-blur rounded-pill px-4 py-2 border border-white border-opacity-25">
                        <i class="fas fa-clock me-2 text-warning"></i>
                        <span class="small fw-bold text-uppercase tracking-widest"><?php echo $store_row['opening_hours']; ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Filter Tabs -->
            <ul class="nav nav-pills mb-4 justify-content-center">
                <li class="nav-item">
                    <a class="nav-link <?php echo($filter == 'all') ? 'active bg-success' : 'text-success'; ?>" href="?id=<?php echo $store_id; ?>&filter=all">All</a>
                </li>
                <li class="nav-item ms-2">
                    <a class="nav-link <?php echo($filter == 'promotions') ? 'active bg-danger' : 'text-danger'; ?>" href="?id=<?php echo $store_id; ?>&filter=promotions">
                        Promotions
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<?php require('includes/footer.php'); ?>
