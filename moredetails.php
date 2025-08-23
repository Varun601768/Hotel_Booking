<?php
include('connection.php');

if (isset($_GET['room'])) {
    $room_id = $_GET['room'];
    $query = "SELECT * FROM rooms WHERE room_id = '$room_id'";
    $result = mysqli_query($conn, $query);
    $room = mysqli_fetch_assoc($result);
} else {
    die("Invalid room ID.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $room['room_name']; ?> - Details</title>
    <link rel="stylesheet" href="style.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .room-details {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow);
            transform-style: preserve-3d;
            perspective: 1000px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .room-details:hover {
            transform: translateY(-5px) rotateX(2deg);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }
        
        .room-details img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: var(--shadow);
            transform: translateZ(20px);
            transition: transform 0.3s ease;
            margin-bottom: 2rem;
        }
        
        .room-details:hover img {
            transform: translateZ(30px);
        }
        
        .room-details h2 {
            color: var(--secondary-color);
            font-size: 2.5rem;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .room-details p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            color: #555;
            line-height: 1.8;
        }
        
        .room-details h3 {
            color: var(--accent-color);
            font-size: 1.8rem;
            margin-bottom: 2rem;
        }
        
        .room-details button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem 2rem;
            font-size: 1.2rem;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
            transform: translateZ(10px);
        }
        
        .room-details button:hover {
            background: var(--secondary-color);
            transform: translateY(-3px) translateZ(20px);
            box-shadow: 0 8px 20px rgba(44, 62, 80, 0.4);
        }
        
        .amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .amenity {
            background: var(--light-color);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        
        footer {
            text-align: center;
            padding: 1.5rem;
            background: var(--secondary-color);
            color: white;
            margin-top: 3rem;
        }
        
        @media (max-width: 768px) {
            .room-details {
                padding: 1.5rem;
                margin: 1rem;
            }
            
            .room-details h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body>
    <?php include('header.php')?>

    <main>
        <div class="room-details">
            <img src="<?php echo $room['room_image']; ?>" alt="<?php echo $room['room_name']; ?>">
            <h2><?php echo $room['room_name']; ?></h2>
            <p><?php echo $room['description']; ?></p>
            
            <!-- Add amenities if available in your database -->
            <div class="amenities">
                <span class="amenity">WiFi</span>
                <span class="amenity">AC</span>
                <span class="amenity">TV</span>
                <span class="amenity">Mini Bar</span>
                <span class="amenity">Room Service</span>
            </div>
            
            <h3>Price: <?php echo $room['price']; ?>₹ per night</h3>
            <button onclick="window.location.href='booking.php?room=<?php echo $room['room_id']; ?>'">BOOK NOW</button>
        </div>
    </main>

    <footer>
        <p>© 2024 Hotel Management. All rights reserved.</p>
    </footer>
</body>

</html>