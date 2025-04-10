<?php
include('top2.php');
// book_details.php (separate file)
$dotenvPath = __DIR__ . '/.env'; 

if (file_exists($dotenvPath)) {
    $lines = file($dotenvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
} else {
    die(".env file not found.");
}

// Read database credentials from environment variables
$servername = $_ENV['DB_SERVERNAME'] ?? null;
$username = $_ENV['DB_USERNAME'] ?? null;
$password = $_ENV['DB_PASSWORD'] ?? null;
$dbname = $_ENV['DB_DATABASE'] ?? null;

// Check if all necessary variables are set
if ($servername === null || $username === null || $password === null || $dbname === null) {
    die("Database credentials not set in .env file.");
}


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
    echo "<button class='back-btn' onclick='window.history.back()'>Back</button>";
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
<link rel="stylesheet" href="styles2.css">
<?php include('bottom.php'); ?>