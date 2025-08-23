<?php
include('connection.php');

$query_rooms = "SELECT * FROM rooms";
$result_rooms = mysqli_query($conn, $query_rooms);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Rooms</title>
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
            padding: 0;
            margin: 0;
        }
        
        main {
            padding: 2rem;
        }
        
        .room-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .room-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            transform-style: preserve-3d;
            perspective: 1000px;
            position: relative;
        }
        
        .room-card:hover {
            transform: translateY(-10px) rotateX(2deg);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }
        
        .room-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .room-card:hover img {
            transform: scale(1.05);
        }
        
        .room-content {
            padding: 1.5rem;
            position: relative;
            z-index: 2;
            background: white;
        }
        
        .room-card h2 {
            color: var(--secondary-color);
            font-size: 1.5rem;
            margin: 0 0 0.5rem 0;
        }
        
        .room-card p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .room-card h3 {
            color: var(--accent-color);
            font-size: 1.3rem;
            margin: 0 0 1.5rem 0;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .room-card button {
            flex: 1;
            min-width: 120px;
            padding: 0.8rem;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            transform: translateZ(10px);
        }
        
        .room-card button:first-child {
            background: var(--secondary-color);
            color: white;
        }
        
        .room-card button:last-child {
            background: var(--primary-color);
            color: white;
        }
        
        .room-card button:hover {
            transform: translateY(-3px) translateZ(20px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .room-card button:first-child:hover {
            background: #1a252f;
        }
        
        .room-card button:last-child:hover {
            background: #2980b9;
        }
        
        footer {
            text-align: center;
            padding: 1.5rem;
            background: var(--secondary-color);
            color: white;
            margin-top: 3rem;
        }
        
        @media (max-width: 768px) {
            .room-list {
                grid-template-columns: 1fr;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            .room-card button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php include('header.php')?>

    <main>
        <div class="room-list">
            <?php while ($row = mysqli_fetch_assoc($result_rooms)) { ?>
                <div class="room-card">
                    <img src="<?php echo $row['room_image']; ?>" alt="<?php echo $row['room_name']; ?>">
                    <div class="room-content">
                        <h2><?php echo $row['room_name']; ?></h2>
                        <p><?php echo $row['description']; ?></p>
                        <h3>Price: <?php echo $row['price']; ?>₹ per night</h3>
                        <div class="button-group">
                            <button onclick="window.location.href='moredetails.php?room=<?php echo $row['room_id']; ?>'">DETAILS</button>
                            <button onclick="window.location.href='booking.php?room=<?php echo $row['room_id']; ?>'">BOOK NOW</button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>

    <footer>
        <p>© 2024 Hotel Management. All rights reserved.</p>
    </footer>
</body>

</html>