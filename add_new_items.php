<?php
require_once 'includes/db.php';

echo "<h2>Adding Supermarket and Electronics Items</h2>";

// 1. Ensure Supermarket Store exists (Chandarana)
$supermarket_query = mysqli_query($con, "SELECT id FROM stores WHERE category = 'Supermarket' LIMIT 1");
if(mysqli_num_rows($supermarket_query) > 0) {
    $sm_store = mysqli_fetch_assoc($supermarket_query);
    $sm_store_id = $sm_store['id'];
} else {
    mysqli_query($con, "INSERT INTO stores (name, description, category, image) VALUES ('Chandarana', 'Supermarket chain', 'Supermarket', 'supermarket.jpg')");
    $sm_store_id = mysqli_insert_id($con);
}

// 2. Ensure Electronics Store exists
$electronics_query = mysqli_query($con, "SELECT id FROM stores WHERE category = 'electronics' LIMIT 1");
if(mysqli_num_rows($electronics_query) > 0) {
    $el_store = mysqli_fetch_assoc($electronics_query);
    $el_store_id = $el_store['id'];
} else {
    mysqli_query($con, "INSERT INTO stores (name, description, category, image) VALUES ('Tech Store', 'All electronics', 'electronics', 'tech.jpg')");
    $el_store_id = mysqli_insert_id($con);
}

// 3. Insert Supermarket Items
$supermarket_items = [
    ['Cheddar Cheese', 'Delicious aged cheddar cheese', 4.50, 'cheese.jpg'],
    ['Mozzarella Cheese', 'Fresh mozzarella for pizza and pasta', 5.00, 'cheese.jpg'],
    ['Whole Milk 1L', 'Fresh whole cow milk', 1.50, 'milk.jpg'],
    ['Almond Milk 1L', 'Plant-based almond milk', 2.50, 'milk.jpg'],
    ['Penne Pasta 500g', 'Premium wheat penne pasta', 2.00, 'pasta.jpg'],
    ['Spaghetti 500g', 'Long, thin, solid, cylindrical pasta', 1.80, 'pasta.jpg'],
    ['Indomie Chicken Flavor', 'Instant noodles chicken flavor pack', 0.50, 'indomie.jpg'],
    ['Indomie Onion Chicken', 'Instant noodles onion chicken flavor', 0.50, 'indomie.jpg'],
    ['Apples (1kg)', 'Fresh red apples', 5.00, 'apple.jpg'],
    ['Bananas (Bunch)', 'Sweet ripe bananas', 2.00, 'banana.jpg'],
    ['Oranges (1kg)', 'Juicy oranges', 4.00, 'orange.jpg'],
    ['Chocolate Bar', 'Rich milk chocolate bar', 1.20, 'candy.jpg'],
    ['Gummy Bears', 'Fruity gummy bears candy', 1.00, 'candy.jpg']
];

foreach ($supermarket_items as $item) {
    $name = mysqli_real_escape_string($con, $item[0]);
    $desc = mysqli_real_escape_string($con, $item[1]);
    $price = $item[2];
    $image = mysqli_real_escape_string($con, $item[3]);
    
    // Check if exists
    $check = mysqli_query($con, "SELECT id FROM products WHERE name = '$name' AND store_id = '$sm_store_id'");
    if(mysqli_num_rows($check) == 0) {
        mysqli_query($con, "INSERT INTO products (store_id, name, category, description, price, image, stock) VALUES ('$sm_store_id', '$name', 'supermarket', '$desc', '$price', '$image', 100)");
        echo "Added Supermarket item: $name<br>";
    } else {
        echo "Supermarket item $name already exists<br>";
    }
}

// 4. Insert Electronics Items
$electronics_items = [
    ['Bluetooth Speaker', 'Portable wireless bluetooth speaker', 45.00, 'speaker.jpg'],
    ['Smart Speaker', 'Voice controlled smart home speaker', 80.00, 'speaker.jpg'],
    ['Wireless Headphones', 'Noise-cancelling wireless headphones', 120.00, 'headphones.jpg'],
    ['Fast Charger USB-C', '30W fast wall charger', 15.00, 'charger.jpg'],
    ['Desktop Computer', 'High-end desktop PC for office and gaming', 850.00, 'computer.jpg'],
    ['Gaming Laptop', '15-inch gaming laptop with RTX 4060', 1200.00, 'laptop.jpg'],
    ['Business Laptop', 'Thin and light laptop for work', 950.00, 'laptop.jpg']
];

foreach ($electronics_items as $item) {
    $name = mysqli_real_escape_string($con, $item[0]);
    $desc = mysqli_real_escape_string($con, $item[1]);
    $price = $item[2];
    $image = mysqli_real_escape_string($con, $item[3]);
    
    // Check if exists
    $check = mysqli_query($con, "SELECT id FROM products WHERE name = '$name' AND store_id = '$el_store_id'");
    if(mysqli_num_rows($check) == 0) {
        mysqli_query($con, "INSERT INTO products (store_id, name, category, description, price, image, stock) VALUES ('$el_store_id', '$name', 'electronics', '$desc', '$price', '$image', 50)");
        echo "Added Electronics item: $name<br>";
    } else {
        echo "Electronics item $name already exists<br>";
    }
}

echo "<br><b>All items processed successfully!</b>";
?>
