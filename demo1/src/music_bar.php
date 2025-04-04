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

// Query to retrieve music data
$sql = "SELECT id, name, image_path FROM music LIMIT 20";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo '<div class="music-scroll-bar">';
    echo '<div class="music-scroll-container">';

    while ($row = $result->fetch_assoc()) {
        $musicId = $row["id"];
        $musicTitle = htmlspecialchars($row["name"]); 
        $imagePath = htmlspecialchars($row["image_path"]);

        echo '<a href="music_details.php?id=' . $musicId . '" class="music-card">';
        echo '<img src="' . $imagePath . '" alt="' . $musicTitle . '">';
        echo '<p>' . $musicTitle . '</p>';
        echo '</a>';
    }

    echo '</div>';
    echo '</div>';
} else {
    echo "<p>No music found.</p>";
}

$conn->close();
?>


<style>
.music-scroll-bar { 
    overflow-x: auto;
    white-space: nowrap;
    padding: 10px 0;
}

.music-scroll-container { 
    display: inline-flex;
}

.music-card { 
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

.music-card p { 
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 180px;
}

.music-card img { 
    max-width: 100%;
    height: auto;
}
</style>