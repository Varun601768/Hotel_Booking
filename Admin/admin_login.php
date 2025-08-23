<?php
session_start();
include('../connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Encrypt the password

    $stmt = $conn->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['admin'] = $username;
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Invalid Username or Password');</script>";
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
    <title>Admin Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --shadow: 0 10px 20px rgba(0,0,0,0.2);
            --inset-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
            --box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
        }
        
        .login-box {
            width: 400px;
            background: white;
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            transition: all 0.5s ease;
        }
        
        .login-box:hover {
            transform: translateY(-5px) rotateX(5deg);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .login-box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
        
        .login-box h2 {
            color: var(--dark-color);
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.8rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
        }
        
        .input-box {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .input-box input {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: var(--inset-shadow);
        }
        
        .input-box input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .input-box span {
            position: absolute;
            left: 1rem;
            top: 1rem;
            padding: 0 0.5rem;
            color: #777;
            pointer-events: none;
            transition: all 0.3s ease;
            background: white;
        }
        
        .input-box input:focus ~ span,
        .input-box input:valid ~ span {
            top: -0.6rem;
            left: 0.8rem;
            font-size: 0.8rem;
            color: var(--primary-color);
            background: white;
        }
        
        .submit-box {
            margin-top: 2rem;
        }
        
        .submit-box input {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .submit-box input:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .submit-box input:active {
            transform: translateY(0);
        }
        
        @media (max-width: 480px) {
            .login-box {
                width: 90%;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Admin Login</h2>
    <form action="admin_login.php" method="POST">
        <div class="input-box">
            <input type="text" name="username" required>
            <span>Username</span>
        </div>
        <div class="input-box">
            <input type="password" name="password" required>
            <span>Password</span>
        </div>
        <div class="submit-box">
            <input type="submit" value="Login">
        </div>
    </form>
</div>

</body>
</html>