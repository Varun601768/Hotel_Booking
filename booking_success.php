<?php
// Start the session to track user login
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Successful</title>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: url(2.png);
            color: #333;
            text-align: center;
        }

        header {
            background-color: rgba(7, 7, 7, 0.7);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        footer {
            background-color: rgba(7, 7, 7, 0.7);
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .message-container {
            background-color: rgba(7, 7, 7, 0.7);
            padding: 30px;
            max-width: 600px;
            margin: 50px auto;
            color: white;
            border-radius: 10px;
            text-align: center;
        }

        .message-container h2 {
            font-size: 2em;
            margin-bottom: 20px;
        }

        .message-container p {
            font-size: 1.2em;
            margin-bottom: 20px;
        }

        .message-container a {
            color: #004080;
            font-size: 1.1em;
            text-decoration: none;
            margin-top: 20px;
            display: inline-block;
        }

        .message-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <header>
        <h1>Booking Successful</h1>
    </header>

    <div class="message-container">
        <h2>Thank you for your booking!</h2>
        <p>Your room has been successfully booked. A confirmation email has been sent to the address you provided.</p>
        <p>Check your booking details and make necessary changes if needed.</p>
        <a href="dashboard.php">Go to Dashboard</a> <!-- Redirect to the user's dashboard -->
    </div>

    <footer>
        <p>DESIGNED BY VARUN M C</p>
    </footer>
</body>

</html>
