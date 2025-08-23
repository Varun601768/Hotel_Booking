<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $fullName, $email, $hashedPassword);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
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
    <title>Register</title>
    <link rel="stylesheet" href="logi.css">
</head>
<body>

<section>
    <div class="register-box">
    <form action="registration.php" method="POST"> 
                <h2>Register</h2>
                <div class="input-box">
                    <span class="icon"><i class="bi bi-person"></i></span>
                    <input type="text" name="fullName" placeholder="Full Name" required>
                    <label>Full Name</label>
                </div>
                <div class="input-box">
                    <span class="icon"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" placeholder="Email" required>
                    <label>Email</label>
                </div>
                <div class="input-box">
                    <span class="icon"><i class="bi bi-passport-fill"></i></span>
                    <input type="password" name="password" placeholder="Password" required>
                    <label>Password</label>
                </div>
                <div class="input-box">
                    <span class="icon"><i class="bi bi-passport-fill"></i></span>
                    <input type="password" name="confirmPassword" placeholder="Confirm Password" required>
                    <label>Confirm Password</label>
                </div>
                <div class="terms">
                    <label><input type="checkbox" required> I agree to the Terms and Conditions</label>
                </div>
                <button type="submit">Register</button>

                <div class="login_link">
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </div>
            </form>
        </div>
    </section>

</body>
</html>
