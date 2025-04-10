<?php
// Database connection setup
$dotenvPath = __DIR__ . '/.env'; // Assuming .env is in the same directory as this script

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

// Query to retrieve poster data
$sql = "SELECT id, name, image_path FROM Posters LIMIT 20"; // Updated column name to 'name'
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo '<div class="poster-scroll-bar">';
    echo '<div class="poster-scroll-container">';

    while ($row = $result->fetch_assoc()) {
        $posterId = $row["id"];
        $posterTitle = htmlspecialchars($row["name"]); // Updated column name to 'name'
        $imagePath = htmlspecialchars($row["image_path"]);

        echo '<a href="poster_details.php?id=' . $posterId . '" class="poster-card">';
        echo '<img src="' . $imagePath . '" alt="' . $posterTitle . '">';
        echo '<p>' . $posterTitle . '</p>';
        echo '</a>';
    }

    echo '</div>';
    echo '</div>';
} else {
    echo "<p>No posters found.</p>";
}

$conn->close();
?>


<style>
.poster-scroll-bar { 
    overflow-x: auto;
    white-space: nowrap;
    padding: 10px 0;
}

.poster-scroll-container { 
    display: inline-flex;
}

.poster-card { 
    flex: 0 0 auto;
    margin: 10px;
    border: 1px solid #ccc;
    padding: 10px;
    width: 200px;
    text-align: center;
    display: inline-block;
    text-decoration: none;
    color: inherit;
}

.poster-card p { 
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
}

.poster-card img { 
    max-width: 100%;
    height: auto;
}
</style>