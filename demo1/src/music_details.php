<?php
include('top2.php');
// music_details.php (separate file)
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

$musicId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

if ($musicId === null) {
    echo "<p>Invalid music ID.</p>";
    $conn->close();
    exit;
}

$sql = "SELECT * FROM music WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepared statement error: " . $conn->error);
}

$stmt->bind_param("i", $musicId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<h2>" . htmlspecialchars($row["name"]) . "</h2>";
    echo "<img src='" . htmlspecialchars($row["image_path"]) . "' style='max-width: 300px;'><br>";
    echo "<p>Description: " . htmlspecialchars($row["description"]) . "</p>";
    echo "<p>Price: $" . htmlspecialchars($row["price"]) . "</p>";
    echo '
        <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="type" value="music">
            <input type="hidden" name="id" value="' . $musicId . '">
            <input type="hidden" name="name" value="' . htmlspecialchars($row["name"]) . '">
            <input type="hidden" name="price" value="' . htmlspecialchars($row["price"]) . '">
            <input type="hidden" name="image" value="' . htmlspecialchars($row["image_path"]) . '">
            <button type="submit">Add to Cart</button>
        </form>';
    echo "<button class='back-btn' onclick='window.history.back()'>Back</button>";
} else {
    echo "<p>Music not found.</p>";
}

$stmt->close();
$conn->close();
?>

<script>
function addToCart(musicId) {
    alert("Music ID " + musicId + " added to cart!");
    // Implement your cart logic here
}
</script>

<!-- Link the updated CSS file -->
<link rel="stylesheet" href="styles2.css">
<?php include('bottom.php'); ?>

