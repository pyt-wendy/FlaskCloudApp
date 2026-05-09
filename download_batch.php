<?php
require_once 'includes/db.php';

$items = [
    ["url" => "https://images.squarespace-cdn.com/content/v1/53419ed7e4b0f92a7a66ecdf/1433128798666-5A3HQNXI72EH3L4OFADF/image-asset.jpeg", "file" => "speaker_real.jpg", "sql" => "UPDATE products SET image='speaker_real.jpg' WHERE name LIKE '%Bluetooth Speaker%'"],
    ["url" => "https://framerusercontent.com/images/FrhAUHYEREr9SKM6BRBNclCi8.webp", "file" => "smart_speaker.webp", "sql" => "UPDATE products SET image='smart_speaker.webp' WHERE name LIKE '%Smart Speaker%'"],
    ["url" => "https://mir-s3-cdn-cf.behance.net/project_modules/hd/ade84a107575139.5faa6596a5a4b.jpg", "file" => "headphones_real.jpg", "sql" => "UPDATE products SET image='headphones_real.jpg' WHERE name LIKE '%Wireless Headphones%'"],
    ["url" => "https://m.media-amazon.com/images/I/41VRPVLmMQL._AC_UF1000,1000_QL80_.jpg", "file" => "charger_real.jpg", "sql" => "UPDATE products SET image='charger_real.jpg' WHERE name LIKE '%Fast Charger USB-C%'"],
    ["url" => "https://images.squarespace-cdn.com/content/v1/5a4b6d1f9f07f5d01c10f2b6/020317a1-21c2-4a99-98f0-7c9b83f02daa/desk_july2023.jpg?format=2500w", "file" => "computer_real.jpg", "sql" => "UPDATE products SET image='computer_real.jpg' WHERE name LIKE '%Desktop Computer%'"],
    ["url" => "https://rog.asus.com/media/15972247821.jpg", "file" => "gaming_laptop.jpg", "sql" => "UPDATE products SET image='gaming_laptop.jpg' WHERE name LIKE '%Gaming Laptop%'"],
    ["url" => "https://thumbs.dreamstime.com/b/modern-black-desk-workspace-highperformance-laptop-showcase-professional-tech-product-photography-business-355163030.jpg", "file" => "laptop_real.jpg", "sql" => "UPDATE products SET image='laptop_real.jpg' WHERE name LIKE '%Business Laptop%'"],
    
    // Batch 2
    ["url" => "https://organic-public.s3.us-west-1.amazonaws.com/product/c2992402-da2c-11eb-af58-0a8b933a216c/6899a8a8bfe8f9.05340139.jpg", "file" => "carrots_real.jpg", "sql" => "UPDATE products SET image='carrots_real.jpg' WHERE name LIKE '%Organic Carrots%'"],
    ["url" => "https://kathysfresh.co.ke/wp-content/uploads/2024/02/top_red_apples_6pk2-scaled.webp", "file" => "apples_real.webp", "sql" => "UPDATE products SET image='apples_real.webp' WHERE name LIKE '%Red Apples%'"],
    ["url" => "https://images.milkandmore.co.uk/image/upload/w_iw/f_auto,q_auto:eco/d_back_up_image.jpg,w_1200,c_scale/v1/products/72775_3.jpg", "file" => "orange_juice_real.jpg", "sql" => "UPDATE products SET image='orange_juice_real.jpg' WHERE name LIKE '%Fresh Orange Juice%'"],
    
    // Missing Reverts Fetching New URLs Found By Agent
    ["url" => "https://cdn.mafrservices.com/sys-master-root/h62/hd2/12456763654174/43306_Main.jpg", "file" => "tuzo_real.jpg", "sql" => "UPDATE products SET image='tuzo_real.jpg' WHERE name LIKE '%Tuzo Milk%'"],
    ["url" => "https://www.oaks.delivery/wp-content/uploads/Copy-of-Copy-of-Social-Media-Product-Ad-800-x-800-px-82-5.png", "file" => "exe_real.png", "sql" => "UPDATE products SET image='exe_real.png' WHERE name LIKE '%Exe Wheat Flour%'"],
    ["url" => "https://cdn.mafrservices.com/sys-master-root/hc6/hfd/17328329523230/22909_main.jpg", "file" => "ketepa_real.jpg", "sql" => "UPDATE products SET image='ketepa_real.jpg' WHERE name LIKE '%Ketepa Pride%'"],
    ["url" => "https://greenspoon.co.ke/wp-content/uploads/2024/11/greenspoon_Blue_band_250g-2006.jpg", "file" => "blueband_real.jpg", "sql" => "UPDATE products SET image='blueband_real.jpg' WHERE name LIKE '%Blue Band Margarine%'"],
    ["url" => "https://www.goodlife.co.ke/wp-content/uploads/2021/06/72900.jpg", "file" => "dettol_real.jpg", "sql" => "UPDATE products SET image='dettol_real.jpg' WHERE name LIKE '%Dettol Antiseptic%'"],
    ["url" => "https://farmerschoice.co.ke/wp-content/uploads/2025/06/Meaty-Beef-Sausages-400g.png", "file" => "farmers_choice_real.png", "sql" => "UPDATE products SET image='farmers_choice_real.png' WHERE name LIKE '%Farmer\\'s Choice Sausages%'"]
];

$success = 0;
foreach($items as $item) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $item['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    $data = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if($httpcode == 200 && $data) {
        file_put_contents("uploads/" . $item['file'], $data);
        if(mysqli_query($con, $item['sql'])) {
            $success++;
        }
    } else {
        echo "Failed: {$item['url']} - HTTP $httpcode\n";
    }
}
echo "Synced $success items out of " . count($items) . ".\n";
?>
