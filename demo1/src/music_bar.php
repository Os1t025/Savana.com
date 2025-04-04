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

// Query to retrieve music data
$sql = "SELECT id, title, song_image FROM music LIMIT 20"; // Updated column names
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo '<div class="music-scroll-bar">'; // Changed class name for clarity
    echo '<div class="music-scroll-container">'; // Changed class name for clarity

    while ($row = $result->fetch_assoc()) {
        $musicId = $row["id"]; // Changed variable name for clarity
        $musicTitle = htmlspecialchars($row["title"]); // Updated column name
        $imagePath = htmlspecialchars($row["song_image"]); // Updated column name

        echo '<a href="music_details.php?id=' . $musicId . '" class="music-card">'; // Link to music_details.php
        echo '<img src="' . $imagePath . '" alt="' . $musicTitle . '">';
        echo '<p>' . $musicTitle . '</p>';
        echo '</a>';
    }

    echo '</div>';
    echo '</div>';
} else {
    echo "<p>No music found.</p>"; // Changed message for clarity
}

$conn->close();
?>

<style>
.music-scroll-bar { /* Changed class name for clarity */
    overflow-x: auto;
    white-space: nowrap;
    padding: 10px 0;
}

.music-scroll-container { /* Changed class name for clarity */
    display: inline-flex;
}

.music-card { /* Changed class name for clarity */
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

.music-card p { /* Changed class name for clarity */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
}

.music-card img { /* Changed class name for clarity */
    max-width: 100%;
    height: auto;
}
</style>