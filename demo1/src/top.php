<?php 
session_start(); // Start the session to access session variables
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savana</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        // button click to go to signin
        function redirectToSignIn() {
            window.location.href = 'signin.php'; 
        }

        // button click to go to signup
        function redirectToSignUp() {
            window.location.href = 'signup.php'; 
        }

        // button click to log out
        function logout() {
            window.location.href = 'logout.php'; 
        }
    </script>
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="logo.png" alt="Logo" />
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Search for books, posters, movies, and music...">
            <button>Search</button>
        </div>
        <div class="header-buttons">
            <?php if (isset($_SESSION['username'])): ?>
                <!-- User is logged in, show profile button with name -->
                <a href="profile.php">
                    <button class="profile-btn">
                        Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </button>
                </a>
                <!-- Logout button -->
                <button class="logout-btn" onclick="logout()">Log Out</button>
                <!-- Cart button with item count, now it links to cart.php -->
                <a href="cart.php" aria-label="0 items in cart" class="nav-a nav-a-2 nav-progressive-attribute" id="nav-cart">
                    <div id="nav-cart-count-container">
                        <span id="nav-cart-count" aria-hidden="true" class="nav-cart-count nav-cart-0 nav-progressive-attribute nav-progressive-content">0</span>
                        <span class="nav-cart-icon nav-sprite"></span>
                    </div>
                </a>
            <?php else: ?>
                <!-- User is not logged in, show sign-in and sign-up buttons -->
                <button class="signin-btn" onclick="redirectToSignIn()">Sign In</button>
                <button class="signup-btn" onclick="redirectToSignUp()">Sign Up</button>
            <?php endif; ?>
        </div>
    </header>
</body>
</html>







