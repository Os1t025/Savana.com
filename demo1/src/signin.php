<?php
include('database.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // use cases of username exist, password verification, and none blanks
        $check_user_sql = "SELECT * FROM Users WHERE username=?";
        $stmt = $conn->prepare($check_user_sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['username'] = $username;
                $_SESSION['user_id'] = $user['id']; 
                header("Location: index.php");
                exit();
            } else {
                echo "Error: Incorrect password.";
            }
        } else {
            echo "Error: Username not found.";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Error: Both fields are required.";
    }
}
?>
<!-- start of html for signin -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header">
        <h1>Sign In to Savana</h1>
    </header>

    <div class="signin-form">
        <form action="signin.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Sign In</button>
        </form>

        <div class="signup-link">
            <p>New to Savana?</p>
            <!-- link to signup page -->
            <a href="signup.php" class="btn">Create your Savana account</a>
        </div>
    </div>

    <?php include('bottom.php'); ?>
</body>
