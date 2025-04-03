<?php
session_start();
require_once 'database.php';

$user_id = $_SESSION['user_id']; 
$query = "SELECT username, email, phone_number, password FROM users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $new_password = $_POST['new_password'];

    if (empty($username) || empty($email) || empty($phone_number)) {
        $error_message = "All fields are required except for the password.";
    } else {
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $query = "UPDATE users SET username = ?, email = ?, phone_number = ?, password = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssi", $username, $email, $phone_number, $hashed_password, $user_id);
        } else {
            $query = "UPDATE users SET username = ?, email = ?, phone_number = ? WHERE user_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $username, $email, $phone_number, $user_id);
        }

        if ($stmt->execute()) {
            $success_message = "Profile updated successfully!";
            $_SESSION['username'] = $username;
        } else {
            $error_message = "Error updating profile. Please try again later.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile | Savana</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <header class="header">
        <div class="logo">
            <h1>Savana</h1>
        </div>
        <div class="header-buttons">
            <a href="top.php"><button class="back-btn">Back to Top</button></a>
        </div>
    </header>

    <div class="profile-container">
        <div class="profile-header">
            <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
        </div>
        <?php
        if (isset($success_message)) {
            echo "<div class='success-message'>$success_message</div>";
        }
        if (isset($error_message)) {
            echo "<div class='error-message'>$error_message</div>";
        }
        ?>

        <div class="profile-form-container">
            <form method="POST" action="profile.php">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="new_password">New Password (Leave blank to keep the current password)</label>
                    <input type="password" id="new_password" name="new_password">
                </div>

                <div class="form-group">
                    <button type="submit">Update Profile</button>
                </div>
            </form>
        </div>
    </div>

    <?php include('bottom.php'); ?>

</body>
</html>



















