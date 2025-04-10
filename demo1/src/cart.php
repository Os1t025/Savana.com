<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cartItems = [];
$totalPrice = 0;

$stmt = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $cartItems[] = $row;
    $totalPrice += $row['price']; // total calculation
}
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart - Savana</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .cart-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }
        .cart-table th,
        .cart-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ccc;
        }
        .cart-table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .product-image-thumb {
            max-width: 80px;
            border-radius: 5px;
        }
        .remove-btn {
            background-color: #dc3545;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .remove-btn:hover {
            background-color: #c82333;
        }
        .cart-total {
            text-align: right;
            font-size: 20px;
            margin-top: 20px;
            font-weight: bold;
        }
        .proceed-checkout {
        background-color: #28a745;
        color: white;
        padding: 10px 20px;
        border: none;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 20px;
        }

        .proceed-checkout:hover {
        background-color: #218838;
        }
    </style>
</head>
<body>
    <!-- Header -->
<div class="header">
    <div class="logo" style="display: flex; align-items: center;">
        <img src="logo.png" alt="Savana Logo" style="height: 40px; margin-right: 10px;">
        <h1 style="font-size: 24px; color: white;">Cart</h1>
    </div>
    <div class="header-buttons">
        <a href="logout.php"><button>Log Out</button></a>
        <a href="top.php"><button>Back to Home</button></a>
    </div>
</div>


    <!-- Cart Section -->
    <div class="main-content cart-container">
        <h2>Your Cart</h2>
        <?php if (count($cartItems) === 0): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Remove</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><img class="product-image-thumb" src="<?php echo htmlspecialchars($item['image']); ?>" alt="Product"></td>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form method="post" action="remove_from_cart.php" onsubmit="return confirm('Are you sure you want to remove this item?');">
                                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="remove-btn">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <p class="cart-total">Total: $<?php echo number_format($totalPrice, 2); ?></p>

            <form action="checkout.php" method="post">
                <button type="submit" class="proceed-checkout">Proceed to Checkout</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>


