<?php
include('connection.php');

$query_rooms = "SELECT * FROM rooms";
$result_rooms = mysqli_query($conn, $query_rooms);

$query_facilities = "SELECT * FROM facilities";
$result_facilities = mysqli_query($conn, $query_facilities);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    :root {
        --primary: #2563eb;
        --primary-light: #3b82f6;
        --secondary: #1e40af;
        --accent: #dc2626;
        --light: #f8fafc;
        --dark: #1e293b;
        --gray: #64748b;
        --light-gray: #e2e8f0;
        --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s ease;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: #f8fafc;
        color: var(--dark);
        line-height: 1.6;
    }

    /* Marquee with subtle style */
    marquee {
            background: linear-gradient(90deg, #00c9ff 0%, #92fe9d 100%);
            color: white;
            font-size: 1.2rem;
            font-weight: 600;
            padding: 15px 0;
            box-shadow: 0 4px 15px rgba(0, 201, 255, 0.3);
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
            transform: perspective(500px) rotateX(10deg);
            margin-bottom: 30px;
        }
    /* Intro section - clean and modern */
    .intro {
        background: white;
        padding: 2.5rem;
        margin: 2rem auto;
        max-width: 1200px;
        border-radius: 12px;
        box-shadow: var(--shadow-md);
        text-align: center;
        border: 1px solid var(--light-gray);
    }

    .intro p {
        font-size: 1.1rem;
        margin-bottom: 2rem;
        color: var(--gray);
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .intro button {
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.8rem 1.8rem;
        font-size: 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: var(--transition);
        font-weight: 500;
    }

    .intro button:hover {
        background: var(--secondary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Section headings */
    h2 {
        text-align: center;
        font-size: 2rem;
        margin: 3rem 0;
        color: var(--dark);
        font-weight: 700;
        position: relative;
    }

    h2:after {
        content: '';
        display: block;
        width: 80px;
        height: 4px;
        background: var(--primary);
        margin: 0.5rem auto 0;
        border-radius: 2px;
    }

    /* Room cards - clean grid layout */
    .room-cards {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    .room-card {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: var(--transition);
        border: 1px solid var(--light-gray);
    }

    .room-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
    }

    .room-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .room-card-content {
        padding: 1.5rem;
    }

    .room-card h3 {
        font-size: 1.25rem;
        color: var(--dark);
        margin: 0 0 0.5rem 0;
        font-weight: 600;
    }

    .room-card .price {
        color: var(--accent);
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0.5rem 0;
    }

    .room-card p {
        color: var(--gray);
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .button-group {
        display: flex;
        gap: 12px;
    }

    .room-card button {
        flex: 1;
        padding: 0.6rem;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
        font-size: 0.9rem;
    }

    .room-card button.bk1 {
        background: var(--dark);
        color: white;
    }

    .room-card button:not(.bk1) {
        background: var(--primary);
        color: white;
    }

    .room-card button:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    /* Main action buttons */
    .btn {
        display: block;
        margin: 3rem auto;
        background: var(--primary);
        color: white;
        border: none;
        padding: 0.8rem 1.8rem;
        font-size: 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: var(--transition);
        font-weight: 500;
    }

    .btn:hover {
        background: var(--secondary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Testimonials section */
    #testimonials {
        padding: 4rem 1.5rem;
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        color: white;
    }

    #testimonials h2 {
        color: white;
    }

    #testimonials h2:after {
        background: white;
    }

    .testimonial-card {
        background: white;
        padding: 1.5rem;
        border-radius: 12px;
        color: var(--dark);
        height: auto;
        box-shadow: var(--shadow-md);
    }

    .testimonial-card i.fa-user {
        font-size: 2rem;
        color: var(--primary);
        background: #e0e7ff;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .testimonial-text {
        font-style: italic;
        margin-bottom: 1rem;
        color: var(--dark);
    }

    .testimonial-author {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--dark);
    }

    .testimonial-card i.fa-star {
        color: #f59e0b;
        margin: 0 1px;
    }

    /* Footer */
    footer {
        background: var(--dark);
        color: white;
        padding: 3rem 1.5rem;
    }

    .footer-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto 2rem;
    }

    footer h4 {
        color: white;
        margin-bottom: 1rem;
        font-size: 1.1rem;
        font-weight: 600;
    }

    footer ul {
        list-style: none;
        padding: 0;
    }

    footer ul li {
        margin-bottom: 0.5rem;
    }

    footer ul li a {
        color: var(--light-gray);
        text-decoration: none;
        transition: var(--transition);
        font-size: 0.9rem;
    }

    footer ul li a:hover {
        color: white;
    }

    footer p {
        text-align: center;
        color: var(--light-gray);
        font-size: 0.9rem;
        margin-top: 2rem;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .intro {
            padding: 1.5rem;
        }
        
        .room-cards {
            grid-template-columns: 1fr;
            padding: 0 1rem;
        }
        
        .button-group {
            flex-direction: column;
        }
        
        h2 {
            font-size: 1.75rem;
        }
    }
</style>
</head>

<body>
    <?php include('header.php')?>

    <main>
        <marquee>Hello Discount closed Book Now!! Hurry Up</marquee>
        
        <section class="intro">
            <p>Discover unparalleled luxury and comfort at our hotel. Whether you're traveling for business or leisure, our world-class amenities and exceptional service will ensure an unforgettable stay.</p>
            <button onclick="window.location.href='about.php'">Explore More</button>
        </section>

        <h2>Our Rooms</h2>
        <div class="room-cards">
            <?php while ($row = mysqli_fetch_assoc($result_rooms)) { ?>
                <div class="room-card">
                    <h3><?php echo $row['room_name']; ?></h3>
                    <img src="<?php echo $row['room_image']; ?>" alt="<?php echo $row['room_name']; ?>">
                    <h3 class="price"><?php echo $row['price']; ?>₹</h3>
                    <p><?php echo $row['description']; ?></p>
                    <div class="button-group">
                        <button onclick="handleBookNow('<?php echo $row['room_id']; ?>')">BOOK NOW</button>
                        <button class="bk1" onclick="redirectToMoreDetails('<?php echo $row['room_id']; ?>')">MORE DETAILS</button>
                    </div>
                </div>
            <?php } ?>
        </div>
        <button onclick="window.location.href='morerooms.php'" id="bk" type="button" class="btn">MORE ROOMS>></button>

        <h2>Our Facilities</h2>
        <div class="room-cards">
            <?php while ($row = mysqli_fetch_assoc($result_facilities)) { ?>
                <div class="room-card">
                    <h3><?php echo $row['facility_name']; ?></h3>
                    <img src="<?php echo $row['facility_image']; ?>" alt="<?php echo $row['facility_name']; ?>">
                </div>
            <?php } ?>
        </div>
        <button onclick="window.location.href='facility.php'" id="bk" type="button" class="btn">MORE FACILITY>></button>
        
        <section id="testimonials">
            <h2>What Our Guests Say</h2>
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <i class="fa-solid fa-user"></i>
                            <p class="testimonial-text">"The best hotel experience I've ever had! The staff was incredibly friendly, and the room was spotless. Highly recommended!"</p>
                            <p class="testimonial-author">- John Doe</p>
                            <div>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <i class="fa-solid fa-user"></i>
                            <p class="testimonial-text">"Amazing service and beautiful rooms. The food was delicious, and the facilities were top-notch. Will definitely visit again!"</p>
                            <p class="testimonial-author">- Jane Smith</p>
                            <div>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <i class="fa-solid fa-user"></i>
                            <p class="testimonial-text">"Great location, comfortable rooms, and exceptional customer service. A wonderful place to stay during our vacation."</p>
                            <p class="testimonial-author">- Emily Johnson</p>
                            <div>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="testimonial-card">
                            <i class="fa-solid fa-user"></i>
                            <p class="testimonial-text">"Fantastic experience from start to finish. The spa was incredibly relaxing, and the rooms were luxurious."</p>
                            <p class="testimonial-author">- Michael Brown</p>
                            <div>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<br><br><br><br><br><br><br><br>
        <footer>
            <div class="footer-content">
                <div>
                    <h4>Contact Us</h4>
                    <ul>
                        <li><a href="#">Phone: +8277621878</a></li>
                        <li><a href="#">Email: hotel@ourhotel.com</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="home.php">Home</a></li>
                        <li><a href="facility.php">Facilities</a></li>
                        <li><a href="morerooms.php">Rooms</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Follow Us</h4>
                    <ul>
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Instagram</a></li>
                        <li><a href="#">Twitter</a></li>
                    </ul>
                </div>
            </div>
            <p>DESIGNED BY VARUN M C</p>
        </footer>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function redirectToMoreDetails(roomId) {
            window.location.href = `moredetails.php?room=${roomId}`;
        }

        function handleBookNow(roomId) {
            // Check if user is logged in or registered
            if (userIsLoggedIn()) {
                window.location.href = `booking.php?room=${roomId}`;
            } else {
                alert('Please log in or register to book a room.');
                window.location.href = 'login.php';
            }
        }

        function userIsLoggedIn() {
            return localStorage.getItem('isLoggedIn') === 'true';
        }

        window.onload = function () {
            if (userIsLoggedIn()) {
                document.getElementById('loginButton').style.display = 'none';
                document.getElementById('registerButton').style.display = 'none';
                document.getElementById('dashboardButton').style.display = 'inline-block';
            } else {
                document.getElementById('loginButton').style.display = 'inline-block';
                document.getElementById('registerButton').style.display = 'inline-block';
                document.getElementById('dashboardButton').style.display = 'none';
            }
        };

        // Initialize Swiper with 3D coverflow effect
        var swiper = new Swiper(".swiper-container", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            loop: true,
            coverflowEffect: {
                rotate: 0,
                stretch: 0,
                depth: 200,
                modifier: 2,
                slideShadows: true,
            },
            breakpoints: {
                320: {
                    slidesPerView: 1,
                },
                768: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });
    </script>
</body>

</html>