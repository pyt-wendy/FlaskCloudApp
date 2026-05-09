<?php
require_once 'includes/db.php';

echo "<h2>Refining Product Catalog and Uniqueifying Images</h2>";

// 1. Uniqueify Existing Product Images
$image_mapping = [
    'Mango Passion Juice (1L)' => 'mango_juice.png',
    'Zinger Burger' => 'zinger_burger.png',
    'Whopper Meal' => 'whopper_meal.png',
    'Beef Meat Pie' => 'meat_pie.png',
    'Chocolate Fudge Cake' => 'fudge_cake.png',
    'BBQ Chicken Pizza' => 'bbq_pizza.png',
    'Fresh Spinach Bunch' => 'spinach.png',
    'Panadol Advance' => 'panadol.jpg',
    'Vitamin C Tablets' => 'vitamin_c.webp',
    'First Aid Kit' => 'first_aid.jpg',
    'Large French Fries' => 'fries_real.png',
    '6 Chicken Nuggets' => 'nuggets_real.jpg',
    'Cat Toys Pack' => 'cat_toys.png',
    'Smartphone X' => 'phone.jpg',
    'Laptop Pro' => 'laptop.jpg',
    'Wireless Headphones' => 'headphones.jpg'
];

foreach ($image_mapping as $name => $img) {
    mysqli_query($con, "UPDATE products SET image = '$img' WHERE name = '$name'");
    echo "Updated $name with unique image: $img<br>";
}

// 2. Add More Products to Various Stores
$new_products = [
    // Electronics (Tech World)
    ['Smart Watch Ultra', 'electronics', 15000, 18000, 'headphones.jpg', 'Advanced health tracking and GPS.', 40, 'Tech World'],
    ['10-inch Tablet', 'electronics', 35000, 42000, 'laptop.jpg', 'Portable and powerful for study and work.', 25, 'Tech World'],
    ['Gaming Mouse', 'electronics', 4500, 5500, 'headphones.jpg', 'RGB lighting and high-precision sensor.', 100, 'Tech World'],
    
    // Fast Food (Burger King / KFC / Galitos)
    ['Crispy Tacos (3pk)', 'food', 850, 950, 'food.jpg', 'Crunchy tacos with seasoned beef and cheese.', 50, 'Burger King'],
    ['Steak & Chips Meal', 'food', 1200, 1500, 'food.jpg', 'Grilled tender steak with a side of crispy fries.', 30, 'Galitos'],
    ['Coleslaw Large', 'food', 250, 300, 'food.jpg', 'Fresh and creamy cabbage salad.', 100, 'KFC'],
    
    // Specialty (Pet Store / Drinks 24/7)
    ['Premium Wine (750ml)', 'drinks', 3500, 4200, 'drink.jpg', 'Rich red wine with notes of oak and berry.', 20, 'Drinks 24/7'],
    ['Energy Drink (500ml)', 'drinks', 200, 250, 'drink.jpg', 'Maximum boost for your busy day.', 200, 'Drinks 24/7'],
    ['Pet Bed Large', 'pet store', 4500, 5500, 'petstore.png', 'Soft and comfortable bed for large dogs.', 15, 'Pet Store'],
    
    // Pharmacy (Goodlife)
    ['Sunscreen SPF 50', 'pharmacy', 1200, 1500, 'pharmacy.jpg', 'Broad spectrum protection for all skin types.', 45, 'Goodlife'],
    ['Hand Sanitizer 500ml', 'pharmacy', 350, 450, 'pharmacy.jpg', '75% Alcohol based deep cleaning gel.', 150, 'Goodlife'],
    ['Reusable Face Masks', 'pharmacy', 500, 600, 'pharmacy.jpg', 'Triple layer protection, pack of 3.', 300, 'Goodlife']
];

foreach ($new_products as $p) {
    $name = mysqli_real_escape_string($con, $p[0]);
    $cat = mysqli_real_escape_string($con, $p[1]);
    $price = $p[2];
    $old_price = $p[3];
    $img = mysqli_real_escape_string($con, $p[4]);
    $desc = mysqli_real_escape_string($con, $p[5]);
    $stock = $p[6];
    $store_name = $p[7];
    
    // Get store ID
    $s_res = mysqli_query($con, "SELECT id FROM stores WHERE name = '$store_name'");
    if ($s_row = mysqli_fetch_assoc($s_res)) {
        $store_id = $s_row['id'];
        
        // Check if exists
        $check = mysqli_query($con, "SELECT id FROM products WHERE name = '$name' AND store_id = '$store_id'");
        if (mysqli_num_rows($check) == 0) {
            mysqli_query($con, "INSERT INTO products (store_id, name, category, description, price, old_price, image, stock) VALUES ('$store_id', '$name', '$cat', '$desc', '$price', '$old_price', '$img', '$stock')");
            echo "Added new product: $name to $store_name<br>";
        } else {
            echo "Skipped existing product: $name<br>";
        }
    }
}

echo "<br><b>Catalog Refinement Complete!</b>";
?>
