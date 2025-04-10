<?php
$dotenvPath = __DIR__ . '/.env'; 

if (file_exists($dotenvPath)) {
    $lines = file($dotenvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
} else {
    die(".env file not found.");
}

$servername = $_ENV['DB_SERVERNAME'] ?? null;
$username = $_ENV['DB_USERNAME'] ?? null;
$password = $_ENV['DB_PASSWORD'] ?? null;
$dbname = $_ENV['DB_DATABASE'] ?? null;

if (!$servername || !$username || !$password || !$dbname) {
    die("Database credentials not set in .env file.");
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$musicId = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : null;
if ($musicId === null) {
    echo "<p>Invalid music ID.</p>";
    $conn->close();
    exit;
}

$sql = "SELECT * FROM music WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $musicId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc(); ?>

    <h2><?= htmlspecialchars($row["name"]) ?></h2>
    <img src="<?= htmlspecialchars($row["image_path"]) ?>" style="max-width: 300px;"><br>
    <p>Description: <?= htmlspecialchars($row["description"]) ?></p>
    <p>Price: $<?= htmlspecialchars($row["price"]) ?></p>

    <form method="POST" action="add_to_cart.php">
        <input type="hidden" name="type" value="music">
        <input type="hidden" name="id" value="<?= $musicId ?>">
        <input type="hidden" name="name" value="<?= htmlspecialchars($row["name"]) ?>">
        <input type="hidden" name="price" value="<?= $row["price"] ?>">
        <input type="hidden" name="image" value="<?= htmlspecialchars($row["image_path"]) ?>">
        <button type="submit">Add to Cart</button>
    </form>

<?php } else {
    echo "<p>Music not found.</p>";
}

$stmt->close();
$conn->close();
?>
