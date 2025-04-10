<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get all current cart items from the DB for this user
$stmt = $conn->prepare("SELECT product_id, name, price, image, quantity FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$cart_items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Save each cart item as an order
$order_stmt = $conn->prepare("INSERT INTO orders (user_id, product_id, name, price, image, quantity) VALUES (?, ?, ?, ?, ?, ?)");

foreach ($cart_items as $item) {
    $order_stmt->bind_param(
        "iisdsi",
        $user_id,
        $item['product_id'],
        $item['name'],
        $item['price'],
        $item['image'],
        $item['quantity']
    );
    $order_stmt->execute();
}
$order_stmt->close();

// Clear cart in DB
$delete_stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
$delete_stmt->bind_param("i", $user_id);
$delete_stmt->execute();
$delete_stmt->close();

// Clear session cart
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Savana</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header">
        <div class="logo" style="display: flex; align-items: center;">
            <img src="logo.png" alt="Savana Logo" style="height: 40px; margin-right: 10px;">
        </div>
        <div class="header-buttons">
            <a href="logout.php"><button>Log Out</button></a>
            <a href="index.php"><button>Back to Home</button></a>
        </div>
    </div>

    <div class="main-content">
        <h2>Thank You for Shopping with Savana!</h2>
        <p>Your order has been received. We hope to see you again soon!</p>
    </div>
</body>
</html>
