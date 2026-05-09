<?php
require_once 'includes/db.php';

echo "<h2>Populating Realistic Carrefour Products and Updating Prices</h2>";

// 1. Copy generated images to uploads/
$brain_dir = "C:\\Users\\ADMIN\\.gemini\\antigravity\\brain\\77dd3749-d955-4975-afe2-81fcd20a32ab\\";
$uploads_dir = "C:\\xampp\\htdocs\\onlineshop\\uploads\\";

$copy_map = [
    'cheese_1774547031513.png' => 'cheese.jpg',
    'pasta_1774547061166.png' => 'pasta.jpg',
    'candy_1774547820784.png' => 'candy.jpg',
    'speaker_1774547856038.png' => 'speaker.jpg',
    'charger_1774547970358.png' => 'charger.jpg'
];

foreach ($copy_map as $src => $dest) {
    if (file_exists($brain_dir . $src)) {
        copy($brain_dir . $src, $uploads_dir . $dest);
        echo "Copied $src to $dest<br>";
    }
}

// Fallbacks for missing generated ones
if (!file_exists($uploads_dir . 'laptop.jpg')) { copy($uploads_dir . 'headphones.jpg', $uploads_dir . 'laptop.jpg'); }
if (!file_exists($uploads_dir . 'computer.jpg')) { copy($uploads_dir . 'headphones.jpg', $uploads_dir . 'computer.jpg'); }
if (!file_exists($uploads_dir . 'indomie.jpg')) { copy($uploads_dir . 'food.jpg', $uploads_dir . 'indomie.jpg'); }
if (!file_exists($uploads_dir . 'apple.jpg')) { copy($uploads_dir . 'grocery.jpg', $uploads_dir . 'apple.jpg'); }
if (!file_exists($uploads_dir . 'banana.jpg')) { copy($uploads_dir . 'grocery.jpg', $uploads_dir . 'banana.jpg'); }
if (!file_exists($uploads_dir . 'orange.jpg')) { copy($uploads_dir . 'grocery.jpg', $uploads_dir . 'orange.jpg'); }
if (!file_exists($uploads_dir . 'milk.jpg')) { copy($uploads_dir . 'milk_jug_1773769756647.png', $uploads_dir . 'milk.jpg'); }
if (!file_exists($uploads_dir . 'bread.jpg')) { copy($uploads_dir . 'bread_loaf_1773769963010.png', $uploads_dir . 'bread.jpg'); }
if (!file_exists($uploads_dir . 'sugar.jpg')) { copy($uploads_dir . 'sugar_pack_1773770173496.png', $uploads_dir . 'sugar.jpg'); }
if (!file_exists($uploads_dir . 'flour.jpg')) { copy($uploads_dir . 'flour_bag_1773770136947.png', $uploads_dir . 'flour.jpg'); }
if (!file_exists($uploads_dir . 'rice.jpg')) { copy($uploads_dir . 'rice_bag_1773770157192.png', $uploads_dir . 'rice.jpg'); }
if (!file_exists($uploads_dir . 'eggs.jpg')) { copy($uploads_dir . 'eggs_carton_1773769979350.png', $uploads_dir . 'eggs.jpg'); }

// 2. Update existing products with KES Realistic Prices
$price_updates = [
    'Cheddar Cheese' => 500,
    'Mozzarella Cheese' => 450,
    'Whole Milk 1L' => 120,
    'Almond Milk 1L' => 350,
    'Penne Pasta 500g' => 200,
    'Spaghetti 500g' => 220,
    'Indomie Chicken Flavor' => 50,
    'Indomie Onion Chicken' => 50,
    'Apples (1kg)' => 400,
    'Bananas (Bunch)' => 150,
    'Oranges (1kg)' => 300,
    'Chocolate Bar' => 150,
    'Gummy Bears' => 100,
    'Bluetooth Speaker' => 3500,
    'Smart Speaker' => 8500,
    'Wireless Headphones' => 5000,
    'Fast Charger USB-C' => 1200,
    'Desktop Computer' => 85000,
    'Gaming Laptop' => 140000,
    'Business Laptop' => 95000,
    // Update older dummy entries too just in case
    'Smartphone X' => 45000,
    'Laptop Pro' => 130000,
    'Denim Jacket' => 2500,
    'Apples (1kg)' => 400, // duplicate but fine
    'Grapes (500g)' => 350,
    'Kales (Bunch)' => 50,
    'Amchicha (Bunch)' => 50,
];

foreach ($price_updates as $name => $price) {
    mysqli_query($con, "UPDATE products SET price = '$price' WHERE name = '$name'");
}
echo "Updated prices for existing products to KES.<br>";

// 3. Ensure Carrefour Store exists
$sm_query = mysqli_query($con, "SELECT id FROM stores WHERE name = 'Carrefour Kenya'");
if(mysqli_num_rows($sm_query) > 0) {
    $c_store = mysqli_fetch_assoc($sm_query);
    $store_id = $c_store['id'];
} else {
    mysqli_query($con, "INSERT INTO stores (name, description, category, image, opening_hours) VALUES ('Carrefour Kenya', 'Leading supermarket chain', 'Supermarket', 'supermarket.jpg', 'Open 8 AM - 10 PM')");
    $store_id = mysqli_insert_id($con);
}

// 4. Insert 30 New Products with KES pricing
$new_items = [
    ['Festive Bread 400g', 'Fresh white sliced bread', 65, 'bread.jpg'],
    ['Supa Loaf 400g', 'Brown bread loaf', 65, 'bread.jpg'],
    ['Brookside Fresh Milk 500ml', 'UHT fresh milk', 70, 'milk.jpg'],
    ['Tuzo Milk 500ml', 'Tuzo whole milk pouch', 60, 'milk.jpg'],
    ['KCC Gold Crown Milk 1L', 'Long life whole milk', 140, 'milk.jpg'],
    ['Nutrameal Sugar 1kg', 'White cane sugar', 180, 'sugar.jpg'],
    ['Sony Sugar 2kg', 'Locally produced white sugar', 350, 'sugar.jpg'],
    ['Santa Lucia Spaghetti 400g', 'Premium pasta', 200, 'pasta.jpg'],
    ['Daawat Basmati Rice 1kg', 'Aromatic pishori rice', 350, 'rice.jpg'],
    ['Pearl Pishori Rice 2kg', 'Grade 1 pure fragrant rice', 600, 'rice.jpg'],
    ['Soko Maize Meal 2kg', 'Fine ugali flour', 150, 'flour.jpg'],
    ['Pembe Maize Meal 2kg', 'Sifted maize meal flour', 145, 'flour.jpg'],
    ['Exe Wheat Flour 2kg', 'White wheat baking flour', 180, 'flour.jpg'],
    ['Rina Vegetable Oil 1L', 'Clear cooking oil', 350, 'food.jpg'],
    ['Elianto Corn Oil 2L', 'Cholesterol free pure corn oil', 800, 'food.jpg'],
    ['Kensalt Table Salt 1kg', 'Iodized table salt', 50, 'food.jpg'],
    ['Ketepa Pride Tea Bags 100s', 'Premium Kenyan tea', 250, 'food.jpg'],
    ['Nescafe Classic 50g', 'Instant coffee pure rich flavor', 400, 'food.jpg'],
    ['Blue Band Margarine 250g', 'Spread for bread', 180, 'food.jpg'],
    ['Farmer\'s Choice Sausages 400g', 'Premium beef sausages', 450, 'grocery.jpg'],
    ['Kenchic Frozen Chicken 1kg', 'Whole frozen chicken', 800, 'grocery.jpg'],
    ['Eggs 1 Tray (30)', 'Farm fresh eggs', 450, 'eggs.jpg'],
    ['Royco Mchuzi Mix 200g', 'Beef flavor seasoning', 120, 'food.jpg'],
    ['Tropical Heat Pilau Masala 50g', 'Blended spices', 80, 'food.jpg'],
    ['Menengai Bar Soap 1kg', 'Cream washing soap', 200, 'supermarket.jpg'],
    ['Omo Washing Powder 500g', 'Tough stain removal powder', 220, 'supermarket.jpg'],
    ['Ariel Washing Powder 1kg', 'Laundry detergent', 400, 'supermarket.jpg'],
    ['Colgate Herbal 100ml', 'Toothpaste for strong teeth', 150, 'pharmacy.jpg'],
    ['Geisha Bath Soap 90g', 'Aloe vera beauty soap', 60, 'pharmacy.jpg'],
    ['Velvex Toilet Paper 4pk', 'Soft white tissue', 160, 'supermarket.jpg'],
    ['Dettol Antiseptic 250ml', 'Liquid antiseptic disinfectant', 400, 'pharmacy.jpg'],
    ['Weetabix Cereal 400g', 'Whole wheat breakfast cereal', 550, 'grocery.jpg'],
    ['Heinz Tomato Ketchup 300ml', 'Thick rich tomato sauce', 350, 'food.jpg']
];

foreach ($new_items as $item) {
    $name = mysqli_real_escape_string($con, $item[0]);
    $desc = mysqli_real_escape_string($con, $item[1]);
    $price = $item[2];
    $image = mysqli_real_escape_string($con, $item[3]);
    
    // Check if exists
    $check = mysqli_query($con, "SELECT id FROM products WHERE name = '$name' AND store_id = '$store_id'");
    if(mysqli_num_rows($check) == 0) {
        mysqli_query($con, "INSERT INTO products (store_id, name, category, description, price, image, stock) VALUES ('$store_id', '$name', 'supermarket', '$desc', '$price', '$image', 100)");
        echo "Added Carrefour item: $name @ KES $price<br>";
    }
}

echo "<br><b>Carrefour Products Processed! Local Prices applied.</b>";
?>
