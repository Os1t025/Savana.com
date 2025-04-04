<?php
// book_details.php (separate file)

$servername = "localhost";
$username = "root";
$password = "mcduruji";
$dbname = "Savana";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$bookId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

if ($bookId === null) {
    echo "<p>Invalid book ID.</p>";
    $conn->close();
    exit;
}

$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepared statement error: " . $conn->error);
}

$stmt->bind_param("i", $bookId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<h2>" . htmlspecialchars($row["title"]) . "</h2>";
    echo "<img src='" . htmlspecialchars($row["book_image"]) . "' style='max-width: 300px;'><br>";
    echo "<p>Description: " . htmlspecialchars($row["description"]) . "</p>";
    echo "<p>Price: $" . htmlspecialchars($row["price"]) . "</p>";
    echo "<button onclick=\"addToCart(" . $bookId . ")\">Add to Cart</button>";
} else {
    echo "<p>book not found.</p>";
}

$stmt->close();
$conn->close();
?>

<script>
function addToCart(bookId) {
    alert("book ID " + bookId + " added to cart!");
    // Implement your cart logic here
}
</script>