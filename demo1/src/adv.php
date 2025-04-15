<?php
$dotenvPath = __DIR__ . '/.env'; 

if (file_exists($dotenvPath)) {
    $lines = file($dotenvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
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

$servername = $_ENV['DB_SERVERNAME'] ?? null;
$username = $_ENV['DB_USERNAME'] ?? null;
$password = $_ENV['DB_PASSWORD'] ?? null;
$dbname = $_ENV['DB_DATABASE'] ?? null;

if ($servername === null || $username === null || $password === null || $dbname === null) {
    die("Database credentials not set in .env file.");
}

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT DISTINCT genre FROM orders 
        JOIN Books ON orders.product_id = Books.id 
        WHERE orders.user_id = ? 
        UNION
        SELECT DISTINCT genre FROM orders 
        JOIN Movies ON orders.product_id = Movies.id 
        WHERE orders.user_id = ? 
        UNION
        SELECT DISTINCT genre FROM orders 
        JOIN Music ON orders.product_id = Music.id 
        WHERE orders.user_id = ? 
        UNION
        SELECT DISTINCT genre FROM orders 
        JOIN Posters ON orders.product_id = Posters.id 
        WHERE orders.user_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('iiii', $user_id, $user_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$user_genres = [];
while ($row = $result->fetch_assoc()) {
    $user_genres[] = $row['genre'];
}

$stmt->close();

$recommended_items = [];
foreach ($user_genres as $genre) {
    $genre = $conn->real_escape_string($genre);
    
    // For Books
    $sql_books = "SELECT id, title, book_image FROM Books WHERE genre LIKE ? LIMIT 5";
    $stmt_books = $conn->prepare($sql_books);
    $stmt_books->bind_param('s', $genre);
    $stmt_books->execute();
    $result_books = $stmt_books->get_result();

    while ($row = $result_books->fetch_assoc()) {
        $recommended_items[] = [
            'type' => 'book',
            'id' => $row['id'],
            'title' => $row['title'],
            'image' => $row['book_image']
        ];
    }
    $stmt_books->close();

    // For Movies
    $sql_movies = "SELECT id, movie_name, movie_image FROM Movies WHERE genre LIKE ? LIMIT 5";
    $stmt_movies = $conn->prepare($sql_movies);
    $stmt_movies->bind_param('s', $genre);
    $stmt_movies->execute();
    $result_movies = $stmt_movies->get_result();

    while ($row = $result_movies->fetch_assoc()) {
        $recommended_items[] = [
            'type' => 'movie',
            'id' => $row['id'],
            'title' => $row['movie_name'],
            'image' => $row['movie_image']
        ];
    }
    $stmt_movies->close();

    // For Music
    $sql_music = "SELECT id, name, image_path FROM Music WHERE genre LIKE ? LIMIT 5";
    $stmt_music = $conn->prepare($sql_music);
    $stmt_music->bind_param('s', $genre);
    $stmt_music->execute();
    $result_music = $stmt_music->get_result();

    while ($row = $result_music->fetch_assoc()) {
        $recommended_items[] = [
            'type' => 'music',
            'id' => $row['id'],
            'title' => $row['name'],
            'image' => $row['image_path']
        ];
    }
    $stmt_music->close();

    // For Posters
    $sql_posters = "SELECT id, name, image_path FROM Posters WHERE genre LIKE ? LIMIT 5";
    $stmt_posters = $conn->prepare($sql_posters);
    $stmt_posters->bind_param('s', $genre);
    $stmt_posters->execute();
    $result_posters = $stmt_posters->get_result();

    while ($row = $result_posters->fetch_assoc()) {
        $recommended_items[] = [
            'type' => 'poster',
            'id' => $row['id'],
            'title' => $row['name'],
            'image' => $row['image_path']
        ];
    }
    $stmt_posters->close();
}

// If no recommendations found, fall back to most popular items
if (empty($recommended_items)) {
    $sql_popular_books = "SELECT id, title, book_image FROM Books LIMIT 5";
    $result_popular_books = $conn->query($sql_popular_books);
    while ($row = $result_popular_books->fetch_assoc()) {
        $recommended_items[] = [
            'type' => 'book',
            'id' => $row['id'],
            'title' => $row['title'],
            'image' => $row['book_image']
        ];
    }

    $sql_popular_movies = "SELECT id, movie_name, movie_image FROM Movies LIMIT 5";
    $result_popular_movies = $conn->query($sql_popular_movies);
    while ($row = $result_popular_movies->fetch_assoc()) {
        $recommended_items[] = [
            'type' => 'movie',
            'id' => $row['id'],
            'title' => $row['movie_name'],
            'image' => $row['movie_image']
        ];
    }

    $sql_popular_music = "SELECT id, name, image_path FROM Music LIMIT 5";
    $result_popular_music = $conn->query($sql_popular_music);
    while ($row = $result_popular_music->fetch_assoc()) {
        $recommended_items[] = [
            'type' => 'music',
            'id' => $row['id'],
            'title' => $row['name'],
            'image' => $row['image_path']
        ];
    }

    $sql_popular_posters = "SELECT id, name, image_path FROM Posters LIMIT 5";
    $result_popular_posters = $conn->query($sql_popular_posters);
    while ($row = $result_popular_posters->fetch_assoc()) {
        $recommended_items[] = [
            'type' => 'poster',
            'id' => $row['id'],
            'title' => $row['name'],
            'image' => $row['image_path']
        ];
    }
}

echo '<div class="recommendations-container">';
echo '<div class="recommendations-scroll-bar">';
echo '<div class="recommendations-scroll-container">';

foreach ($recommended_items as $item) {
    echo '<a href="' . $item['type'] . '_details.php?id=' . $item['id'] . '" class="recommendation-card">';
    echo '<img src="' . $item['image'] . '" alt="' . $item['title'] . '">';
    echo '<p>' . $item['title'] . '</p>';
    echo '</a>';
}

echo '</div>';
echo '</div>';
echo '</div>';


$conn->close();
?>
<style>
.recommendations-container {
    margin: 20px 0;
}

.recommendations-scroll-bar {
    overflow-x: auto;
    white-space: nowrap;
    padding: 10px 0;
}

.recommendations-scroll-container {
    display: inline-flex;
    gap: 10px;
}

.recommendation-card {
    flex: 0 0 auto;
    margin: 10px;
    border: 1px solid #ccc;
    padding: 10px;
    width: 200px;
    text-align: center;
    display: inline-block;
    text-decoration: none; 
    color: inherit; 
    box-sizing: border-box; 
}

.recommendation-card p {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
}

.recommendation-card img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 0 auto;
}
</style>



        