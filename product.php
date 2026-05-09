<?php
require('includes/header.php');

$id = get_safe_value($con, $_GET['id']);
if ($id == '') {
    redirect('index.php');
}

$res = mysqli_query($con, "SELECT * FROM products WHERE id='$id'");
$row = mysqli_fetch_assoc($res);

if (!$row) {
    redirect('index.php');
}
?>

<div class="row mt-4">
    <div class="col-md-5">
        <img src="uploads/<?php echo $row['image']; ?>" class="img-fluid rounded" alt="<?php echo $row['name']; ?>">
    </div>
    <div class="col-md-7">
        <h2><?php echo $row['name']; ?></h2>
        <h3 class="text-success">Ksh <?php echo $row['price']; ?></h3>
        <p class="mt-3"><?php echo nl2br($row['description']); ?></p>
        
        <?php if ($row['stock'] > 0) { ?>
            <form action="cart.php" method="post" class="mt-4">
                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="qty" class="col-form-label">Quantity:</label>
                    </div>
                    <div class="col-auto">
                        <input type="number" name="qty" id="qty" class="form-control" value="1" min="1" max="<?php echo $row['stock']; ?>">
                    </div>
                    <div class="col-auto">
                        <button type="submit" name="add_to_cart" class="btn btn-warning btn-lg">Add to Cart</button>
                    </div>
                </div>
            </form>
        <?php
}
else { ?>
            <p class="text-danger fs-4">Out of Stock</p>
        <?php
}?>
    </div>
</div>
<div class="row mt-5" id="aiRecommendationsContainer" style="display:none;">
    <div class="col-12">
        <h4 class="mb-4"><i class="fas fa-magic text-warning me-2"></i>AI Suggestions: You Might Also Like</h4>
        <div class="row" id="aiRecommendationsList">
            <!-- Products will be loaded here -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productId = <?php echo $id; ?>;
    const container = document.getElementById('aiRecommendationsContainer');
    const list = document.getElementById('aiRecommendationsList');
    
    fetch('ai_api.php?action=recommend_products&id=' + productId)
        .then(res => res.json())
        .then(data => {
            if (data && data.length > 0) {
                container.style.display = 'flex';
                data.forEach(item => {
                    const col = document.createElement('div');
                    col.className = 'col-md-4 mb-3';
                    col.innerHTML = `
                        <div class="card h-100 border-0 shadow-sm">
                            <img src="uploads/${item.image}" class="card-img-top p-3" alt="${item.name}" style="height: 150px; object-fit: contain;">
                            <div class="card-body text-center d-flex flex-column">
                                <h6 class="card-title">${item.name}</h6>
                                <p class="card-text text-success fw-bold">Ksh ${item.price}</p>
                                <a href="product.php?id=${item.id}" class="btn btn-outline-warning mt-auto rounded-pill btn-sm">View</a>
                            </div>
                        </div>
                    `;
                    list.appendChild(col);
                });
            }
        });
});
</script>

<?php require('includes/footer.php'); ?>
