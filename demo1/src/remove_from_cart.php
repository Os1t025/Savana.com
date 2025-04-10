<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['item_id'])) {
    header("Location: cart.php");
    exit();
}

$item_id = $_POST['item_id'];
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $item_id, $user_id);
$stmt->execute();
$stmt->close();

header("Location: cart.php");
exit();
