<?php include('top.php'); ?>
<script src="script.js"></script>
<div class="main-content">
    <h2>Welcome to Savana!</h2>
    <p>Explore our collection of books, posters, movies, and music.</p>
    <a href="signup.php" class="btn">New to Savana? Create your account</a>

    <!-- Books Section -->
    <div class="section-label">Music</div>
    <?php include 'music_bar.php'; ?>

    <div class="section-label">Posters</div>
    <?php include 'posters_bar.php'; ?>

    <div class="section-label">Books</div>
    <?php include 'books_bar.php'; ?>

    <div class="section-label">Movies</div>
    <?php include 'movies_bar.php'; ?>
</div>

<?php include('bottom.php'); ?>


