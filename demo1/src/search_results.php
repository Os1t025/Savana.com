<?php
include('database.php');
include('top2.php');
if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $sql = "SELECT * FROM (
                SELECT 'book' AS type, id, title AS name, price, description, book_image AS image_path FROM books WHERE title LIKE ? 
                UNION 
                SELECT 'movie' AS type, id, movie_name AS name, price, description, movie_image AS image_path FROM Movies WHERE movie_name LIKE ? 
                UNION 
                SELECT 'music' AS type, id, name, price, description, image_path FROM music WHERE name LIKE ? 
                UNION 
                SELECT 'poster' AS type, id, name, price, description, image_path FROM Posters WHERE name LIKE ?
            ) AS search_results";
    
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('MySQL prepare failed: ' . $conn->error);
    }

    $searchTerm = "%" . $query . "%";
    $stmt->bind_param("ssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $type = $row['type'];
            $name = $row['name'];
            $price = number_format($row['price'], 2);
            $description = substr($row['description'], 0, 150) . '...';
            $imagePath = $row['image_path'];
            $id = $row['id'];
            ?>
            <div class="search-item">
                <img src="<?php echo $imagePath; ?>" alt="<?php echo $name; ?>">
                <div class="search-item-info">
                    <h3><?php echo $name; ?></h3>
                    <p><?php echo $description; ?></p>
                    <p>Price: $<?php echo $price; ?></p>
                    <button onclick="addToCart(<?php echo $id; ?>, '<?php echo $type; ?>')">Add to Cart</button>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No results found for '$query'</p>";
    }
} else {
    echo "<p>No search query provided.</p>";
}
?>

<script>
function addToCart(itemId, itemType) {
    alert(itemType + " ID " + itemId + " added to cart!");
    // Implement cart logic here, for example, adding the item to a session or local storage
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_to_cart.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.send("id=" + itemId + "&type=" + itemType);

    // You can also update the cart count dynamically using JavaScript after adding an item
    updateCartCount();
}

function updateCartCount() {
    var cartCount = parseInt(localStorage.getItem("cartCount")) || 0;
    localStorage.setItem("cartCount", cartCount + 1);
    document.getElementById("nav-cart-count").textContent = cartCount + 1;
}
</script>

<link rel="stylesheet" href="styles.css">

<?php
include('bottom.php');
?>


