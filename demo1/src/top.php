<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Savana</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function redirectToSignIn() {
            window.location.href = 'signin.php'; 
        }

        function redirectToSignUp() {
            window.location.href = 'signup.php'; 
        }

        function logout() {
            window.location.href = 'logout.php'; 
        }
    </script>

<style>
    .content {
        text-align: center; 
        margin-top: 50px;  
    }

    .welcome-message {
        font-size: 24px;   
        font-weight: bold; 
        color: #333;       
    }

    .explore-message {
        font-size: 18px;   
        color: #666;      
    }
</style>

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
                <a href="profile.php">
                    <button class="profile-btn">
                        Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </button>
                </a>
                
                <button class="logout-btn" onclick="logout()">Log Out</button>
                
                <a href="cart.php" aria-label="0 items in cart" class="nav-a nav-a-2 nav-progressive-attribute" id="nav-cart">
                    <div id="nav-cart-count-container">
                        <span id="nav-cart-count" aria-hidden="true" class="nav-cart-count nav-cart-0 nav-progressive-attribute nav-progressive-content">0</span>
                        <span class="nav-cart-icon nav-sprite"></span>
                    </div>
                </a>
            <?php else: ?>
                <button class="signin-btn" onclick="redirectToSignIn()">Sign In</button>
                <button class="signup-btn" onclick="redirectToSignUp()">Sign Up</button>
            <?php endif; ?>
        </div>
    </header>

    <!-- Add some content here for logged-in users -->
    <div class="content">
    <?php if (isset($_SESSION['username'])): ?>
        <h2 class="welcome-message">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
        <p class="explore-message">Explore our products, manage your account, or check out the latest deals!</p>
        <?php include('index2.php'); ?>
    <?php else: ?>
    <?php endif; ?>
</div>



</body>
</html>








