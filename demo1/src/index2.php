
<div class="main-content">
    <h2>Welcome to Savana!</h2>
    <!-- Books Section -->
    <div class="section">
        <h3>Books</h3>
        <div class="row">
            <?php
            // change password
            $conn = new mysqli('localhost', 'root', 'mcduruji', 'savana');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $sql = "SELECT * FROM Books";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($book = $result->fetch_assoc()) {
                    echo '<div class="col">';
                    echo '<a href="book_details.php?id=' . $book['id'] . '">';
                    echo '<img src="Images/Books/' . basename($book['book_image']) . '" alt="' . $book['title'] . '" class="product-image">'; // Correct path
                    echo '<h4>' . $book['title'] . '</h4>';
                    echo '<p>' . $book['author'] . '</p>';
                    echo '<p>$' . $book['price'] . '</p>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo "No books available.";
            }
            
            $conn->close();
            ?>
        </div>
    </div>

    <!-- Movies Section -->
    <div class="section">
        <h3>Movies</h3>
        <div class="row">
            <?php
            // change password
            $conn = new mysqli('localhost', 'root', 'mcduruji', 'savana');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $sql = "SELECT * FROM Movies";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($movie = $result->fetch_assoc()) {
                    echo '<div class="col">';
                    echo '<a href="movie_details.php?id=' . $movie['id'] . '">';
                    echo '<img src="Image/Movies/' . basename($movie['movie_image']) . '" alt="' . $movie['movie_name'] . '" class="product-image">'; // Correct path
                    echo '<h4>' . $movie['movie_name'] . '</h4>';
                    echo '<p>' . $movie['genre'] . '</p>';
                    echo '<p>$' . $movie['price'] . '</p>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo "No movies available.";
            }
            
            $conn->close();
            ?>
        </div>
    </div>

    <!-- Music Section -->
    <div class="section">
        <h3>Music</h3>
        <div class="row">
            <?php
            // change password
            $conn = new mysqli('localhost', 'root', 'mduruji', 'savana');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $sql = "SELECT * FROM Music";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($music = $result->fetch_assoc()) {
                    echo '<div class="col">';
                    echo '<a href="music_details.php?id=' . $music['id'] . '">';
                    echo '<img src="Images/Music/' . basename($music['image_path']) . '" alt="' . $music['name'] . '" class="product-image">';
                    echo '<h4>' . $music['name'] . '</h4>';
                    echo '<p>' . $music['artist'] . '</p>';
                    echo '<p>$' . $music['price'] . '</p>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo "No music available.";
            }
            
            $conn->close();
            ?>
        </div>
    </div>

    <!-- Posters Section -->
    <div class="section">
        <h3>Posters</h3>
        <div class="row">
            <?php
            // change password
            $conn = new mysqli('localhost', 'root', 'mcduruji', 'savana');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $sql = "SELECT * FROM Posters";
            $result = $conn->query($sql);
            
            if ($result->num_rows > 0) {
                while($poster = $result->fetch_assoc()) {
                    echo '<div class="col">';
                    echo '<a href="poster_details.php?id=' . $poster['id'] . '">';
                    echo '<img src="Images/Posters/' . basename($poster['image_path']) . '" alt="' . $poster['name'] . '" class="product-image">';
                    echo '<h4>' . $poster['name'] . '</h4>';
                    echo '<p>' . $poster['artist'] . '</p>';
                    echo '<p>$' . $poster['price'] . '</p>';
                    echo '</a>';
                    echo '</div>';
                }
            } else {
                echo "No posters available.";
            }
            
            $conn->close();
            ?>
        </div>
    </div>

</div>

<?php include('bottom.php'); ?>