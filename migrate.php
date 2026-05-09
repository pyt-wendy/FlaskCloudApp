<?php
require_once 'includes/db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Starting Migration...</h2>";

// Use a custom query function for better debugging
function exe($con, $sql)
{
    if (!mysqli_query($con, $sql)) {
        die("<p style='color:red;'>Query failed: " . mysqli_error($con) . "</p><p>SQL: $sql</p>");
    }
    return true;
}

// Add category to stores
exe($con, "ALTER TABLE stores ADD COLUMN IF NOT EXISTS category VARCHAR(50) DEFAULT 'unclassified' AFTER description");

// Add opening_hours to stores
exe($con, "ALTER TABLE stores ADD COLUMN IF NOT EXISTS opening_hours VARCHAR(50) DEFAULT '9 AM - 9 PM' AFTER image");

// Add category to products
exe($con, "ALTER TABLE products ADD COLUMN IF NOT EXISTS category VARCHAR(50) DEFAULT 'unclassified' AFTER name");

// 13. Clear stores for fresh start
exe($con, "DELETE FROM stores");

// Final Store List
$final_stores = [
    // Supermarket
    ['Chandarana', 'Quality and selection', 'Supermarket', 'supermarket.jpg', 'Open until 10 PM'],
    // Pharmacy
    ['Goodlife', 'Your health, our priority', 'Pharmacy', 'pharmacy.jpg', 'Open 24/7'],
    // Fast Food
    ['KFC', 'Finger lickin good', 'Fast Food', 'kfc.jpg', 'Open until Midnight'],
    ['The Bakers', 'Freshly baked treats', 'Fast Food', 'bakers inn.jpg', 'Open until 9 PM'],
    ['Pizza Planet', 'Out of this world pizza', 'Fast Food', 'pizza.jpg', 'Open until 11 PM'],
    ['Galitos', 'Flame grilled chicken', 'Fast Food', 'galitos.jpg', 'Open until Midnight'],
    ['Burger King', 'Be your way', 'Fast Food', 'burger.jpg', 'Open until 11 PM'],
    ['Roast Chicken', 'Luv dat chicken', 'Fast Food', 'roast chicken.jpg', 'Open until 10 PM'],
    // Specialty
    ['Pet Store', 'Everything for your furry friends', 'Specialty', 'petstore.png', 'Open until 8 PM'],
    ['Drinks 24/7', 'Refreshments anytime', 'Specialty', 'drinks soju.jpg', 'Open 24/7'],
    // Electronics
    ['Tech World', 'Best electronics in town', 'Electronics', 'headphones.jpg', 'Open 9 AM - 8 PM']
];

$store_ids = [];
foreach ($final_stores as $s) {
    exe($con, "INSERT INTO stores (name, description, category, image, opening_hours) VALUES ('$s[0]', '$s[1]', '$s[2]', '$s[3]', '$s[4]')");
    $store_ids[$s[0]] = mysqli_insert_id($con);
}

// Clear products for fresh start
exe($con, "DELETE FROM products");

// Comprehensive Product List
$products = [
    // Chandarana: ONLY Vegetables, Fruits, and Fresh Juice
    ['Organic Carrots (1kg)', 'groceries', 120, 150, 'carrots_fresh.png', 'Farm-fresh organic carrots, high in Vitamin A.', 40, $store_ids['Chandarana']],
    ['Red Apples (6pk)', 'groceries', 450, 520, 'apples_red.png', 'Crisp and sweet premium red apples.', 35, $store_ids['Chandarana']],
    ['Fresh Orange Juice (500ml)', 'groceries', 250, 300, 'orange_juice_fresh.png', '100% freshly squeezed orange juice with pulp.', 25, $store_ids['Chandarana']],
    ['Fresh Spinach Bunch', 'groceries', 80, 100, 'grocery.jpg', 'Local nutrient-rich green spinach.', 50, $store_ids['Chandarana']],
    ['Ripe Bananas (Bunch)', 'groceries', 150, 180, 'grocery.jpg', 'Sweet and ripe yellow bananas.', 60, $store_ids['Chandarana']],
    ['Mango Passion Juice (1L)', 'groceries', 450, 550, 'drink.jpg', 'Tropical blend of mango and passion fruit.', 20, $store_ids['Chandarana']],

    // Goodlife
    ['Panadol Advance', 'pharmacy', 250, 300, 'pharmacy.jpg', 'Pain reliever and fever reducer.', 100, $store_ids['Goodlife']],
    ['Vitamin C Tablets', 'pharmacy', 800, 950, 'pharmacy.jpg', 'Immune system support.', 50, $store_ids['Goodlife']],
    ['First Aid Kit', 'pharmacy', 1500, 1800, 'pharmacy.jpg', 'Essential first aid supplies.', 15, $store_ids['Goodlife']],
    // KFC
    ['Zinger Burger', 'food', 650, 750, 'burger.jpg', 'Spicy chicken fillet burger.', 80, $store_ids['KFC']],
    ['Streetwise 2', 'food', 450, 500, 'kfc.jpg', '2 pieces of chicken and regular chips.', 150, $store_ids['KFC']],
    ['Large French Fries', 'food', 250, 300, 'food.jpg', 'Classic golden crispy fries.', 200, $store_ids['KFC']],
    // The Bakers
    ['Beef Meat Pie', 'food', 180, 220, 'bakers inn.jpg', 'Flaky pastry filled with seasoned beef.', 60, $store_ids['The Bakers']],
    ['Chocolate Fudge Cake', 'food', 1500, 1800, 'bakers inn.jpg', 'Rich chocolate layers with smooth fudge.', 10, $store_ids['The Bakers']],
    // Pizza Planet
    ['Pepperoni Passion', 'food', 1200, 1400, 'pizza.jpg', 'Extra pepperoni and extra mozzarella.', 40, $store_ids['Pizza Planet']],
    ['BBQ Chicken Pizza', 'food', 1300, 1500, 'pizza.jpg', 'Grilled chicken, BBQ sauce, and onions.', 35, $store_ids['Pizza Planet']],
    // Galitos
    ['Quarter Chicken & Rice', 'food', 580, 650, 'galitos.jpg', 'Flame-grilled quarter chicken with spicy rice.', 70, $store_ids['Galitos']],
    ['Full Chicken Meal', 'food', 2200, 2500, 'galitos.jpg', 'Perfect for the family feast.', 20, $store_ids['Galitos']],
    // Burger King
    ['Whopper Meal', 'food', 850, 950, 'burger.jpg', 'Flame-grilled beef, fresh toppings, fries & drink.', 50, $store_ids['Burger King']],
    ['6 Chicken Nuggets', 'food', 400, 480, 'food.jpg', 'Crispy golden chicken nuggets.', 100, $store_ids['Burger King']],
    // Roast Chicken
    ['Quarter Roast Chicken', 'food', 550, 600, 'roast chicken.jpg', 'Succulent roast chicken quarter.', 90, $store_ids['Roast Chicken']],
    ['Sticky Wings (6pk)', 'food', 650, 750, 'roast chicken.jpg', 'Chicken wings in a sweet sticky glaze.', 45, $store_ids['Roast Chicken']],
    // Pet Store
    ['Dog Food (5kg)', 'pet store', 2500, 2800, 'petstore.png', 'Nutritious dry adult dog food.', 25, $store_ids['Pet Store']],
    ['Cat Toys Pack', 'pet store', 1200, 1400, 'petstore.png', 'Variety pack of interactive cat toys.', 15, $store_ids['Pet Store']],
    // Drinks 24/7
    ['Premium Vodka (750ml)', 'drinks', 2800, 3200, 'drinks soju.jpg', 'Smooth triple distilled vodka.', 30, $store_ids['Drinks 24/7']],
    ['Craft Beer (6pk)', 'drinks', 1200, 1500, 'drink.jpg', 'Local craft lager brewed to perfection.', 40, $store_ids['Drinks 24/7']],
    // Tech World
    ['Smartphone X', 'electronics', 45000, 50000, 'phone.jpg', 'Latest high-end smartphone with incredible camera.', 50, $store_ids['Tech World']],
    ['Laptop Pro', 'electronics', 130000, 150000, 'laptop.jpg', 'High performance professional laptop.', 30, $store_ids['Tech World']],
    ['Wireless Headphones', 'electronics', 5000, 6000, 'headphones.jpg', 'Noise cancelling bluetooth headphones.', 200, $store_ids['Tech World']]
];

foreach ($products as $p) {
    exe($con, "INSERT INTO products (name, category, price, old_price, image, description, stock, store_id) VALUES ('$p[0]', '$p[1]', '$p[2]', '$p[3]', '$p[4]', '$p[5]', '$p[6]', '$p[7]')");
}

echo "<p style='color:green; font-weight:bold;'>Migration Successful!</p>";
echo "<p>Total Stores: " . count($final_stores) . "</p>";
echo "<p>Total Products: " . count($products) . "</p>";
echo "<p><a href='index.php'>Go to Website</a></p>";
?>
