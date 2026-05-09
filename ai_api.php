<?php
// ai_api.php
require('includes/db.php');
require('includes/functions.php');
require('includes/ai_helper.php');

header('Content-Type: application/json');

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($action == 'search_suggest') {
    $query = isset($_GET['q']) ? trim($_GET['q']) : '';
    if (empty($query)) {
        echo json_encode([]);
        exit;
    }

    // Get a small sample of categories/product names to give context
    $products_res = mysqli_query($con, "SELECT name, category FROM products LIMIT 50");
    $catalog = [];
    if ($products_res) {
        while ($row = mysqli_fetch_assoc($products_res)) {
            $catalog[] = $row['name'] . ' (' . $row['category'] . ')';
        }
    }
    $catalog_str = implode(", ", $catalog);

    $sys = "You are an intelligent e-commerce search assistant. The user is typing a search query. You must reply ONLY with a JSON array of 3-5 suggested search terms or product types that match their query context. Draw inspiration from our catalog if possible. Do NOT use markdown code blocks, just raw JSON array of strings.";
    $prompt = "Catalog samples: $catalog_str\n\nUser Query: $query\n\nSuggest 3-5 search terms in JSON array format.";

    $res = call_gemini_api($prompt, $sys);
    if (isset($res['error'])) {
        echo json_encode([]);
        exit;
    }

    // Clean markdown if AI returned it
    $text = str_replace(['```json', '```'], '', $res['text']);
    $suggestions = json_decode(trim($text), true);

    if (is_array($suggestions)) {
        echo json_encode($suggestions);
    }
    else {
        echo json_encode([]);
    }
    exit;
}

if ($action == 'recommend_products') {
    $product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if (!$product_id) {
        echo json_encode([]);
        exit;
    }

    $res_p = mysqli_query($con, "SELECT name, category, description FROM products WHERE id=$product_id");
    if (!$res_p || mysqli_num_rows($res_p) == 0) {
        echo json_encode([]);
        exit;
    }
    $product = mysqli_fetch_assoc($res_p);

    $sys = "You are a product recommendation engine. The user is viewing a product. Recommend 3 general product categories or item types that go well with it as companions. Reply ONLY with a JSON array of strings. Do not use markdown blocks.";
    $prompt = "Viewing Product: " . $product['name'] . " (Category: " . $product['category'] . ")\nRecommend 3 complementary things in JSON array format.";

    $res = call_gemini_api($prompt, $sys);
    if (isset($res['error'])) {
        echo json_encode([]);
        exit;
    }

    $text = str_replace(['```json', '```'], '', $res['text']);
    $keywords = json_decode(trim($text), true);

    if (!is_array($keywords) || empty($keywords)) {
        echo json_encode([]);
        exit;
    }

    // Search for these keywords in our DB
    $recommended = [];
    foreach ($keywords as $kw) {
        $kw = mysqli_real_escape_string($con, $kw);
        $sql = "SELECT id, name, price, image FROM products WHERE (name LIKE '%$kw%' OR category LIKE '%$kw%') AND id != $product_id LIMIT 1";
        $q = mysqli_query($con, $sql);
        if ($q && $row = mysqli_fetch_assoc($q)) {
            $exists = false;
            foreach ($recommended as $r) {
                if ($r['id'] == $row['id'])
                    $exists = true;
            }
            if (!$exists)
                $recommended[] = $row;
        }
    }

    // If not enough recommendations were found, fallback to same category
    if (count($recommended) < 3) {
        $needed = 3 - count($recommended);
        $cat = mysqli_real_escape_string($con, $product['category']);
        $q = mysqli_query($con, "SELECT id, name, price, image FROM products WHERE category='$cat' AND id != $product_id LIMIT $needed");
        if ($q) {
            while ($row = mysqli_fetch_assoc($q)) {
                $exists = false;
                foreach ($recommended as $r) {
                    if ($r['id'] == $row['id'])
                        $exists = true;
                }
                if (!$exists)
                    $recommended[] = $row;
            }
        }
    }

    echo json_encode(array_slice($recommended, 0, 3));
    exit;
}

if ($action == 'chat') {
    $input = json_decode(file_get_contents('php://input'), true);
    $message = isset($input['message']) ? trim($input['message']) : '';

    if (empty($message)) {
        echo json_encode(['reply' => 'Please ask a question!']);
        exit;
    }

    // Get catalog context
    $products_res = mysqli_query($con, "SELECT name, price, category FROM products LIMIT 50");
    $catalog = [];
    if ($products_res) {
        while ($row = mysqli_fetch_assoc($products_res)) {
            $catalog[] = "{$row['name']} (Ksh {$row['price']}, {$row['category']})";
        }
    }
    $catalog_str = implode(" | ", $catalog);

    $sys = "You are a helpful and friendly AI shopping assistant for our online store. Our partial catalog: $catalog_str. Answer user questions cheerfully, recommend products from our catalog if relevant, and keep responses short and punchy (1-2 paragraphs max). Use basic HTML tags like <b> or <br> for formatting.";

    $res = call_gemini_api($message, $sys);
    if (isset($res['error'])) {
        echo json_encode(['reply' => 'Sorry, I am having trouble connecting to my brain right now.']);
    }
    else {
        // AI response handling
        echo json_encode(['reply' => $res['text']]);
    }
    exit;
}

echo json_encode(['error' => 'Invalid action']);
?>
