<?php
require('includes/header.php');

$query = '';
if (isset($_GET['query'])) {
    $query = mysqli_real_escape_string($con, $_GET['query']);
}

// Search in products table
$sql = "SELECT * FROM products WHERE name LIKE '%$query%' OR description LIKE '%$query%'";
$res = mysqli_query($con, $sql);
?>

<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Search Results</li>
            </ol>
        </nav>
        <h2 class="fw-bold">Results for "<?php echo htmlspecialchars($query); ?>"</h2>
        <p class="text-muted"><?php echo mysqli_num_rows($res); ?> products found</p>
    </div>
</div>

<div class="row">
    <?php if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_assoc($res)) { ?>
            <div class="col-md-3 mb-4">
                <div class="card product-card h-100 border-0 shadow-sm">
                    <img src="uploads/<?php echo $row['image']; ?>" class="card-img-top p-3" alt="<?php echo $row['name']; ?>" style="height: 180px; object-fit: contain;">
                    <div class="card-body text-center d-flex flex-column">
                        <h6 class="card-title"><?php echo $row['name']; ?></h6>
                        <p class="card-text text-success fw-bold">Ksh <?php echo $row['price']; ?></p>
                        <a href="product.php?id=<?php echo $row['id']; ?>" class="btn btn-glovo mt-auto rounded-pill btn-sm">View Details</a>
                    </div>
                </div>
            </div>
        <?php
    }
}
else { ?>
        <div class="col-12 text-center py-5">
            <div class="mb-4">
                <span class="badge bg-danger px-4 py-2 rounded-pill text-uppercase tracking-widest fw-bold shadow-sm">Out of Stock</span>
            </div>
            <i class="fas fa-search fa-3x text-muted mb-4 opacity-50"></i>
            <h3 class="fw-bold">We couldn't find "<?php echo htmlspecialchars($query); ?>"</h3>
            <p class="text-muted">It might be out of stock or unavailable at the moment.<br>Try searching for something else or explore our top categories.</p>
            <div class="mt-4">
                <a href="index.php" class="btn btn-glovo rounded-pill px-4 me-2">Back to Home</a>
                <a href="categories.php" class="btn btn-outline-dark rounded-pill px-4">Browse Categories</a>
            </div>
        </div>
    <?php
}?>
</div>

<?php require('includes/footer.php'); ?>
