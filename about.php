


<?php
include 'connection.php'; // DB Connection

// Fetch About Info
$about_sql = "SELECT * FROM about_info WHERE id=1";
$about_result = mysqli_query($conn, $about_sql);
$about = mysqli_fetch_assoc($about_result);

// Fetch Team Members
$team_sql = "SELECT * FROM management_team";
$team_result = mysqli_query($conn, $team_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us</title>
    <link rel="stylesheet" href="about.css">
</head>
<body>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }
        header {
            background-color: #1a1d1c;
            color: white;
            text-align: center;
            padding: 1.5rem;
        }
        h1 {
            margin: 0;
            font-size: 2.5rem;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .about-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 50px;
        }
        .about-section h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 20px;
        }
        .about-section p {
            font-size: 1.2rem;
            line-height: 1.6;
            text-align: center;
            max-width: 800px;
            margin-bottom: 30px;
        }
        .video-container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            background-color: #333;
            padding: 10px;
        }
        .video-container video {
            width: 100%;
            height: 450px;
            border: none;
        }
        .team-section {
            text-align: center;
            margin-top: 50px;
        }
        .team-section h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 30px;
        }
        /* Team carousel with continuous scroll */
        .team-carousel {
            display: flex;
            justify-content: center;
            overflow: hidden;
            margin: 20px auto;
            max-width: 1000px;
            align-items: center;
            position: relative;
        }
        .carousel-track {
            display: flex;
            animation: scrollImages 20s linear infinite;
        }
        .team-member {
            text-align: center;
            margin: 0 20px;
        }
        .team-carousel img {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }
        .team-member h3 {
            margin: 10px 0;
            font-size: 1.1rem;
            color: #333;
        }
        @keyframes scrollImages {
            0% { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
        }
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
        footer .footer-content {
            display: flex;
            justify-content: space-between;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        footer .footer-content div {
            flex: 1;
            margin: 0 10px;
        }
        footer .footer-content ul {
            list-style-type: none;
            padding: 0;
        }
        footer .footer-content ul li {
            margin: 5px 0;
        }
        footer .footer-content ul li a {
            color: white;
            text-decoration: none;
        }
        nav {
            background-color: black;
            padding: 20px;
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
        }
        .about-images {
            display: flex;
            justify-content: space-around;
            margin-bottom: 50px;
        }
        .about-images img {
            width: 400px;
            height: 400px;
            object-fit: cover;
            border: solid 2px black ;
        }
    </style>
<header>
  <?php  include("header.php"); ?>
    <h1><?= $about['title'] ?></h1>
</header>

<div class="container">
    <div class="about-section">
        <h2>Who We Are</h2>
        <p><?= $about['description'] ?></p>

        <div class="video-container">
            <video controls>
                <source src="upload/<?= $about['video'] ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>

    <div class="team-section">
        <h2>Management Team</h2>
        <div class="team-carousel">
            <div class="carousel-track">
                <?php while($row = mysqli_fetch_assoc($team_result)) { ?>
                    <div class="team-member">
                        <img src="upload/<?= $row['image'] ?>" alt="<?= $row['name'] ?>">
                        <h3><?= $row['name'] ?></h3>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2025 Hotel Booking. All rights reserved.</p>
</footer>
</body>
</html>
