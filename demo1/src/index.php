<?php include('top.php'); ?>
<a href="cart.php" style="position: absolute; top: 60px; right: 200px; text-decoration: none;">
    <img src="cart.png" alt="Cart" style="width: 40px;">
</a>

<div class="main-content">
    <h2>Welcome to Savana!</h2>
    <p>Explore our collection of books, posters, movies, and music.</p>
    <a href="signup.php" class="btn">New to Savana? Create your account</a>

    <!-- Books Section -->
    <?php include 'music_bar.php'; ?>
    <?php include 'posters_bar.php'; ?>
    <?php include 'books_bar.php'; ?>
    <?php include 'movies_bar.php'; ?>
</div>

<?php include('bottom.php'); ?>



