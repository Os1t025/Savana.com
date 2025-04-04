<?php
// Database connection setup
$servername = "localhost";
$username = "root";
$password = "mcduruji";
$dbname = "Savana";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Query to retrieve poster data
$sql = "SELECT id, title, poster_image FROM Posters LIMIT 20"; // Updated column names
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo '<div class="poster-scroll-bar">'; // Changed class name for clarity
    echo '<div class="poster-scroll-container">'; // Changed class name for clarity

    while ($row = $result->fetch_assoc()) {
        $posterId = $row["id"]; // Changed variable name for clarity
        $posterTitle = htmlspecialchars($row["title"]); // Updated column name
        $imagePath = htmlspecialchars($row["poster_image"]); // Updated column name

        echo '<a href="poster_details.php?id=' . $posterId . '" class="poster-card">'; // Link to poster_details.php
        echo '<img src="' . $imagePath . '" alt="' . $posterTitle . '">';
        echo '<p>' . $posterTitle . '</p>';
        echo '</a>';
    }

    echo '</div>';
    echo '</div>';
} else {
    echo "<p>No posters found.</p>"; // Changed message for clarity
}

$conn->close();
?>

<style>
.poster-scroll-bar { /* Changed class name for clarity */
    overflow-x: auto;
    white-space: nowrap;
    padding: 10px 0;
}

.poster-scroll-container { /* Changed class name for clarity */
    display: inline-flex;
}

.poster-card { /* Changed class name for clarity */
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

.poster-card p { /* Changed class name for clarity */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
}

.poster-card img { /* Changed class name for clarity */
    max-width: 100%;
    height: auto;
}
</style>