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
            <button class="signin-btn" onclick="redirectToSignIn()">Sign In</button>
        </div>
        <div>
            <!-- cart button with item count (needs work and fixes) -->
            <a href="/gp/cart/view.html?ref_=nav_cart" aria-label="0 items in cart" class="nav-a nav-a-2 nav-progressive-attribute" id="nav-cart">
                <div id="nav-cart-count-container">
                    <span id="nav-cart-count" aria-hidden="true" class="nav-cart-count nav-cart-0 nav-progressive-attribute nav-progressive-content">0</span>
                    <span class="nav-cart-icon nav-sprite"></span>
                </div>
            </a>
        </div>
    </header>
</body>
</html>



