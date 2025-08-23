<?php
session_start();
include 'connection.php';

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$error_message = ""; // To store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $sql = "SELECT user_id, full_name, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error in SQL statement: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $fullName, $hashedPassword);
        $stmt->fetch();

        if (password_verify($password, $hashedPassword)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['full_name'] = $fullName;
            header("Location: home.php");
            exit();
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "No user found with this email!";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="logi.css"> <!-- Changed from logi.css -->
</head>
<body>

<section>
    <div class="login-box">
        <form action="login.php" method="POST">
            <h2>Login</h2>

            <?php if (!empty($error_message)): ?>
                <p style="color: red; text-align: center;"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>

            <div class="input-box">
                <span class="icon"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" placeholder="Email" required>
                <label>Email</label>
            </div>

            <div class="input-box">
                <span class="icon"><i class="bi bi-clipboard-plus-fill"></i></span>
                <input type="password" name="password" placeholder="Password" required>
                <label>Password</label>
            </div>

            <div class="remember-forgot">
                <label><input type="checkbox"> Remember me</label>
                <a href="#">Forgot Password?</a>
            </div>

            <button type="submit">Login</button>

            <div class="register_link">
                <p>Don't have an account? <a href="registration.php">Register</a></p>
            </div>
        </form>
    </div>
</section>

</body>
</html>
