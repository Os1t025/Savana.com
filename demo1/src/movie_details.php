<?php
// movie_details.php (separate file)

$servername = "localhost";
$username = "root";
$password = "mcduruji";
$dbname = "Savana";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$movieId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;

if ($movieId === null) {
    echo "<p>Invalid movie ID.</p>";
    $conn->close();
    exit;
}

$sql = "SELECT * FROM movies WHERE id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepared statement error: " . $conn->error);
}

$stmt->bind_param("i", $movieId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo "<h2>" . htmlspecialchars($row["movie_name"]) . "</h2>";
    echo "<img src='" . htmlspecialchars($row["movie_image"]) . "' style='max-width: 300px;'><br>";
    echo "<p>Description: " . htmlspecialchars($row["description"]) . "</p>";
    echo "<p>Price: $" . htmlspecialchars($row["price"]) . "</p>";
    echo "<button onclick=\"addToCart(" . $movieId . ")\">Add to Cart</button>";
} else {
    echo "<p>Movie not found.</p>";
}

$stmt->close();
$conn->close();
?>

<script>
function addToCart(movieId) {
    alert("Movie ID " + movieId + " added to cart!");
    // Implement your cart logic here
}
</script>