<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $item_id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    // Store in session (for session-based cart preview)
    $_SESSION['cart'][] = [
        'id' => $item_id,
        'name' => $name,
        'price' => $price,
        'image' => $image
    ];

    // Store in database (add item to cart table)
    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, name, price, image, quantity) VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->bind_param("iisds", $user_id, $item_id, $name, $price, $image);

    if (!$stmt->execute()) {
        echo "Database error: " . $stmt->error;
        exit();
    }

    $stmt->close();
}

header("Location: cart.php");
exit;
