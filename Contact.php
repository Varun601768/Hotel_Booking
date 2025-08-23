<?php
include('connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['fullName'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        echo "<script>alert('Message sent successfully!');</script>";
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
    <title>Contact Us</title>
    <link rel="stylesheet" href="contact.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

    <style>
        /* Basic styles for the layout */
        nav {
            background-color: #0d0e0e5e;
            padding: 20px;
        }
        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 30px;
            display: flex;
            justify-content: center;
            background-color: rgb(56, 53, 53);
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
        }
        footer {
            background-color: black;
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
    </style>
</head>
<body>

<?php include('header.php');?>

<section class="contact">
    <div class="content">
        <h2>Contact Us</h2>
        <p>We would love to hear from you! Whether you have questions about our services, need assistance with booking, or just want to provide feedback, our team is here to help.</p>
    </div>
    <div class="map-container">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3021.759860303227!2d-93.23262348459591!3d44.08758927910968!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x87f7aaf63e4f3b0f%3A0xebe09db4fa48251!2sOwatonna%20Minnesota!5e0!3m2!1sen!2sus!4v1695907172023!5m2!1sen!2sus" 
            width="100%" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy">
        </iframe>
    </div>

    <div class="container">
        <div class="contactInfo">
            <div class="box">
                <div class="icon"><i class="fa-solid fa-location-dot"></i></div>
                <div class="text">
                    <h3>Address</h3>
                    <p>1-43 Sugar camp Road,<br>Owatonna Minnesota,<br>22545521</p>
                </div>
            </div>
            <div class="box">
                <div class="icon"><i class="fa-solid fa-phone"></i></div>
                <div class="text">
                    <h3>Phone</h3>
                    <p>+91 94678956</p>
                </div>
            </div>
            <div class="box">
                <div class="icon"><i class="fa-solid fa-envelope"></i></div>
                <div class="text">
                    <h3>Email</h3>
                    <p>varunmcchinthu@gmail.com</p>
                </div>
            </div>
            <h2 class="txt">Connect with us</h2>
            <ul class="sci">
                <li><a href="#"><i class="fa-brands fa-facebook"></i></a></li>
                <li><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                <li><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                <li><a href="#"><i class="fa-brands fa-linkedin"></i></a></li>
            </ul>
        </div>

        <div class="contactForm">
            <form action="contact.php" method="POST">
                <h2>Send Message</h2>
                <div class="inputBox">
                    <input type="text" name="fullName" required>
                    <span>Full Name</span>
                </div>
                <div class="inputBox">
                    <input type="email" name="email" required>
                    <span>Email</span>
                </div>
                <div class="inputBox">
                    <textarea name="message" required></textarea>
                    <span>Type your Message...</span>
                </div>
                <div class="inputBox">
                    <input type="submit" value="Send">
                </div>
            </form>
        </div>
    </div>
</section>

<script>
    document.querySelector("form").addEventListener("submit", function (event) {
        let name = document.querySelector("input[name='fullName']").value;
        let email = document.querySelector("input[name='email']").value;
        let message = document.querySelector("textarea[name='message']").value;
        let emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

        if (name === "" || email === "" || message === "") {
            alert("All fields are required.");
            event.preventDefault();
        } else if (!email.match(emailPattern)) {
            alert("Please enter a valid email address.");
            event.preventDefault();
        }
    });
</script>

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
                <li><a href="home.html">Home</a></li>
                <li><a href="facility.html">Facilities</a></li>
                <li><a href="morerooms.html">Rooms</a></li>
            </ul>
        </div>
    </div>
    <p>DESIGNED BY VARUN M C</p>
</footer>

</body>
</html>
