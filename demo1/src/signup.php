<?php
include('database.php');

// Check if the form is being submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirmPassword'], $_POST['phone'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        $phone = $_POST['phone'];

        // Validate phone number
        if (!preg_match("/^\+?[0-9]*$/", $phone)) {
            echo "Error: Invalid phone number. Only numbers and an optional '+' are allowed.";
            exit();
        }
        
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the username already exists
        $check_username_sql = "SELECT * FROM users WHERE username=?";
        $stmt = $conn->prepare($check_username_sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo "Error: Username already exists.";
            $stmt->close();
            $conn->close();
            exit();
        }

        // Insert data into the users table
        $insert_sql = "INSERT INTO users (username, email, password, phone_number) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $phone);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: index.php?signup=success");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js"></script>
</head>
<body>
    <header class="header">
        <h1>Create Your Savana Account</h1>
    </header>
    <!-- start of form -->
    <div class="signup-form">
        <form action="signup.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirmPassword">Re-enter Password:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" required>

            <button type="submit">Create Account</button>
        </form>

        <!-- Back Button -->
        <div class="back-link">
            <button onclick="window.location.href='signin.php'">Back to Sign In</button>
        </div>
    </div>

    <?php include('bottom.php'); ?>
</body>
</html>





