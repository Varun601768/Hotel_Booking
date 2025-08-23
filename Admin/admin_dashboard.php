<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}
include('../connection.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
            color: var(--dark-color);
        }
        
        nav {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 2rem;
            box-shadow: var(--shadow);
            position: relative;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        nav h2 {
            font-size: 1.8rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 1.5rem;
        }
        
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        nav ul li a:hover {
            background-color: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        nav ul li a:active {
            transform: translateY(0);
        }
        
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        h1 {
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 2.2rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
        }
        
        .columns {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .box {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .box::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
        
        .box h3 {
            color: var(--dark-color);
            margin-bottom: 1rem;
            font-size: 1.3rem;
            border-bottom: 2px solid #eee;
            padding-bottom: 0.5rem;
        }
        
        .box p {
            font-size: 1.1rem;
            color: #555;
        }
        
        .box p::before {
            content: '•';
            color: var(--primary-color);
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
        }
        
        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                padding: 1rem;
            }
            
            nav ul {
                margin-top: 1rem;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            nav ul li {
                margin: 0.5rem;
            }
            
            .columns {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<?php include('admin_header.php');?>
<!-- Dashboard Content -->
<div class="container">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin']); ?>!</h1>

    <div class="columns">
        <!-- Users Section -->
        <div class="box">
            <h3>Users</h3>
            <?php
            $result = $conn->query("SELECT COUNT(*) AS total FROM users");
            $data = $result->fetch_assoc();
            echo "<p>Total Users: " . htmlspecialchars($data['total']) . "</p>";
            ?>
        </div>

        <!-- Bookings Section -->
        <div class="box">
            <h3>Bookings</h3>
            <?php
            $result = $conn->query("SELECT COUNT(*) AS total FROM bookings");
            $data = $result->fetch_assoc();
            echo "<p>Total Bookings: " . htmlspecialchars($data['total']) . "</p>";
            ?>
        </div>

        <!-- Rooms Section -->
        <div class="box">
            <h3>Rooms</h3>
            <?php
            $result = $conn->query("SELECT COUNT(*) AS total FROM rooms");
            $data = $result->fetch_assoc();
            echo "<p>Total Rooms: " . htmlspecialchars($data['total']) . "</p>";
            ?>
        </div>

        <!-- Facilities Section -->
        <div class="box">
            <h3>Facilities</h3>
            <?php
            $result = $conn->query("SELECT COUNT(*) AS total FROM facilities");
            $data = $result->fetch_assoc();
            echo "<p>Total Facilities: " . htmlspecialchars($data['total']) . "</p>";
            ?>
        </div>

        <!-- User Facilities Section -->
        <div class="box">
            <h3>User Facilities</h3>
            <?php
            $result = $conn->query("SELECT COUNT(*) AS total FROM facility_bookings");
            $data = $result->fetch_assoc();
            echo "<p>Total User Facilities: " . htmlspecialchars($data['total']) . "</p>";
            ?>
        </div>

        <!-- Contact Us Messages -->
        <div class="box">
            <h3>Contact Us Messages</h3>
            <?php
            $result = $conn->query("SELECT COUNT(*) AS total FROM contact_messages");
            $data = $result->fetch_assoc();
            echo "<p>Total Messages: " . htmlspecialchars($data['total']) . "</p>";
            ?>
        </div>
    </div>
</div>

</body>
</html>