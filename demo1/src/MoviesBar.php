<!-- Movies bar should have a div what has 20 divs that contain the cards which displays each movie -->
<!-- Bar has a scroll feature that lets you scroll through the bar like a slideshow -->
<!-- It should be connected to the database so that i can get each movie per movie id and the path to the image -->
<!-- Set up database connection
     Make the bar div
     Make all the Card divs ?-> Is there a way to make reuseable cards in the in PHP like you would in react
     Fill in the cards with the required data
     When each item is clicked, a pop up will show the full information for the item and the option to add to cart
 -->

 <?php
// Database connection setup
$servername = "localhost";
$username = "root";
$password = "mcduruji"; //add your root password
$dbname = "Savana";        //add the database name, I called mine savana

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Query to retrieve movie data
$sql = "SELECT id, movie_name, movie_image FROM movies LIMIT 20"; // Limit to 20 movies
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo '<div class="movie-slideshow">';
    echo '<div class="movie-slideshow-container">';

    while ($row = $result->fetch_assoc()) {
        $movieId = $row["id"];
        $movieTitle = htmlspecialchars($row["movie_name"]);
        $imagePath = htmlspecialchars($row["movie_image"]);

        echo '<div class="movie-card" onclick="showMovieDetails(' . $movieId . ')">';
        echo '<img src="' . $imagePath . '" alt="' . $movieTitle . '">';
        echo '<p>' . $movieTitle . '</p>';
        echo '</div>';
    }

    echo '</div>';
    echo '</div>';
} else {
    echo "<p>No movies found.</p>";
}

$conn->close();
?>

<div id="movieDetailsPopup" class="movie-details-popup">
    <div id="movieDetailsContent"></div>
    <button onclick="closeMovieDetails()">Close</button>
    <button onclick="addToCart()">Add to Cart</button>
</div>

<script>
function showMovieDetails(movieId) {
    document.getElementById('movieDetailsContent').innerHTML = "Loading...";
    fetch('get_movie_details.php?id=' + movieId)
        .then(response => response.text())
        .then(data => {
            document.getElementById('movieDetailsContent').innerHTML = data;
            document.getElementById('movieDetailsPopup').style.display = 'block';
        });
}

function closeMovieDetails() {
    document.getElementById('movieDetailsPopup').style.display = 'none';
}

function addToCart() {
    alert("Movie added to cart!");
    closeMovieDetails();
}
</script>

<style>
.movie-slideshow {
    overflow: scroll; /* Important for slideshow effect */
}

.movie-slideshow-container {
    display: flex; /* Arrange cards horizontally */
    transition: transform 0.5s ease-in-out; /* Smooth slideshow transition */
    width: fit-content; /* Ensure container is wide enough */
}

.movie-card {
    flex: 0 0 auto; /* Prevent cards from shrinking/growing */
    margin: 10px;
    border: 1px solid #ccc;
    padding: 10px;
    width: 200px;
    text-align: center;
    cursor: pointer;
}

.movie-card img {
    max-width: 100%;
    height: auto;
}

.movie-details-popup {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border: 1px solid #ccc;
    z-index: 1000;
}

/* Slideshow navigation (optional) */
.movie-slideshow-nav {
    text-align: center;
    margin-top: 10px;
}

.movie-slideshow-nav button {
    padding: 5px 10px;
    cursor: pointer;
}
</style>

<script>
// Slideshow functionality (optional)
let slideIndex = 0;
const container = document.querySelector('.movie-slideshow-container');
const cardWidth = document.querySelector('.movie-card').offsetWidth + 20; // Card width + margin

function nextSlide() {
    slideIndex = Math.min(slideIndex + 1, container.children.length - 1);
    updateSlide();
}

function prevSlide() {
    slideIndex = Math.max(slideIndex - 1, 0);
    updateSlide();
}

function updateSlide() {
    container.style.transform = `translateX(-${slideIndex * cardWidth}px)`;
}
</script>

<?php
// get_movie_details.php (separate file)

$servername = "localhost";
$username = "root";
$password = "mcduruji"; //add your root password
$dbname = "Savana";        //add the database name, I called mine savana

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
} else {
    echo "<p>Movie not found.</p>";
}

$stmt->close();
$conn->close();
?>