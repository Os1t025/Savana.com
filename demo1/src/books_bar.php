<?php
// Database connection setup
$servername = "localhost";
$username = "root";
$password = "yourpassword";
$dbname = "Savana";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Query to retrieve book data
$sql = "SELECT id, title, book_image FROM books LIMIT 20";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo '<div class="book-scroll-bar">';
    echo '<div class="book-scroll-container">';

    while ($row = $result->fetch_assoc()) {
        $bookId = $row["id"];
        $bookTitle = htmlspecialchars($row["title"]);
        $imagePath = htmlspecialchars($row["book_image"]);

        echo '<a href="book_details.php?id=' . $bookId . '" class="book-card">'; // Link to book_details.php
        echo '<img src="' . $imagePath . '" alt="' . $bookTitle . '">';
        echo '<p>' . $bookTitle . '</p>';
        echo '</a>';
    }

    echo '</div>';
    echo '</div>';
} else {
    echo "<p>No books found.</p>";
}

$conn->close();
?>

<style>
.book-scroll-bar {
    overflow-x: auto;
    white-space: nowrap;
    padding: 10px 0;
}

.book-scroll-container {
    display: inline-flex;
}

.book-card {
    flex: 0 0 auto;
    margin: 10px;
    border: 1px solid #ccc;
    padding: 10px;
    width: 200px;
    text-align: center;
    display: inline-block;
    text-decoration: none; /* Remove default link underline */
    color: inherit; /* Inherit parent color */
}

.book-card p {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
}

.book-card img {
    max-width: 100%;
    height: auto;
}
</style> 