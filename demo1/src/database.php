<?php
$servername = "localhost";
$username = "root";
$password = "Ositopop#23"; //add your root password
$dbname = "savana";        //add the database name, I called mine savana

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['confirmPassword'], $_POST['phone'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirmPassword'];
        $phone = $_POST['phone'];

        if ($password !== $confirmPassword) {
            echo "Error: Passwords do not match.";
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
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