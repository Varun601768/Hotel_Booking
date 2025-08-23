<?php
session_start();
include("connection.php");

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $checkin = $_POST['checkin'];
    $checkout = $_POST['checkout'];
    $roomType = $_POST['roomType'];
    $guests = $_POST['guests'];

    $currentDate = date("Y-m-d");

    // Date Validation
    if ($checkin < $currentDate) {
        $error_message = "Check-in date cannot be in the past.";
    } elseif ($checkout <= $checkin) {
        $error_message = "Check-out date must be after the check-in date.";
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO bookings (user_id, name, email, checkin_date, checkout_date, room_type, guests) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssi", $user_id, $name, $email, $checkin, $checkout, $roomType, $guests);

        if ($stmt->execute()) {
            header("Location: booking_success.php");
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Page</title>
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
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: url(2.png) no-repeat center center fixed;
            background-size: cover;
        }
        
        header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 2rem;
            box-shadow: var(--shadow);
            position: relative;
            z-index: 100;
        }
        
        nav {
            background: var(--dark-color);
            padding: 1rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        nav a {
            color: white;
            text-decoration: none;
            margin: 0 1rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        nav a:hover {
            background-color: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }
        
        #bookingForm {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 2.5rem;
            width: 100%;
            max-width: 500px;
            box-shadow: var(--shadow);
            transform-style: preserve-3d;
            transition: all 0.5s ease;
            position: relative;
            overflow: hidden;
        }
        
        #bookingForm:hover {
            transform: translateY(-5px) rotateX(5deg);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }
        
        #bookingForm::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
        
        #bookingForm h2 {
            color: var(--dark-color);
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
        }
        
        .error-message {
            color: var(--accent-color);
            text-align: center;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        #bookingForm label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        #bookingForm input,
        #bookingForm select {
            width: 100%;
            padding: 0.8rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: var(--inset-shadow);
        }
        
        #bookingForm input:focus,
        #bookingForm select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 1.5rem;
        }
        
        .form-actions button {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .form-actions button[type="submit"] {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }
        
        .form-actions button[type="button"] {
            background: linear-gradient(135deg, var(--accent-color), #c0392b);
            color: white;
        }
        
        .form-actions button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .form-actions button:active {
            transform: translateY(0);
        }
        
        footer {
            background: var(--dark-color);
            color: white;
            text-align: center;
            padding: 1rem;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
        }
        
        @media (max-width: 768px) {
            #bookingForm {
                padding: 1.5rem;
            }
            
            .form-actions {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .form-actions button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
<?php include('header.php')?>

<main>
    <form id="bookingForm" method="POST" action="booking.php" onsubmit="return validateDates()">
        <h2>Room Booking</h2>

        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <label for="name">Full Name:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($_SESSION['full_name']) ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" required>

        <label for="checkin">Check-in Date:</label>
        <input type="date" id="checkin" name="checkin" required>

        <label for="checkout">Check-out Date:</label>
        <input type="date" id="checkout" name="checkout" required>

        <label for="roomType">Room Type:</label>
        <select id="roomType" name="roomType" required>
            <option value="Single">Single Room</option>
            <option value="Double">Double Room</option>
            <option value="Suite">Suite</option>
        </select>

        <label for="guests">Number of Guests:</label>
        <input type="number" id="guests" name="guests" min="1" required>

        <div class="form-actions">
            <button type="submit">Book Now</button>
            <button type="button" onclick="window.location.href='home.php'">Cancel</button>
        </div>
    </form>
</main>

<footer>
    <p>DESIGNED BY VARUN M C</p>
</footer>

<script>
    function validateDates() {
        const checkin = document.getElementById("checkin").value;
        const checkout = document.getElementById("checkout").value;
        const today = new Date().toISOString().split("T")[0];

        if (checkin < today) {
            alert("Check-in date cannot be in the past.");
            return false;
        }
        if (checkout <= checkin) {
            alert("Check-out date must be after the check-in date.");
            return false;
        }
        return true;
    }
</script>
</body>
</html>