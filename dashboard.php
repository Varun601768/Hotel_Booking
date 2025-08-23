<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Check database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch user details
$user_sql = "SELECT * FROM users WHERE user_id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();
$user_stmt->close();

// Fetch user bookings
$sql = "SELECT booking_id, name, email, checkin_date, checkout_date, room_type, guests, status FROM bookings WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}
$stmt->close();

// Fetch user-selected facilities
$sql_facilities = "SELECT f.facility_name, f.facility_image, f.description 
                   FROM facility_bookings fb
                   JOIN facilities f ON fb.facility_id = f.facility_id
                   WHERE fb.user_id = ?";
$stmt_facilities = $conn->prepare($sql_facilities);
$stmt_facilities->bind_param("i", $user_id);
$stmt_facilities->execute();
$result_facilities = $stmt_facilities->get_result();

$facilities = [];
while ($row = $result_facilities->fetch_assoc()) {
    $facilities[] = $row;
}
$stmt_facilities->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3498db;
            --secondary: #2c3e50;
            --accent: #e74c3c;
            --success: #2ecc71;
            --light: #ecf0f1;
            --dark: #34495e;
            --shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            color: var(--dark);
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .profile-header {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            display: flex;
            align-items: center;
            gap: 2rem;
            transform: translateZ(20px);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .profile-header:hover {
            transform: translateY(-5px) translateZ(30px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 8px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), var(--accent));
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: var(--light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            color: var(--primary);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateZ(30px);
        }

        .profile-info h2 {
            margin: 0;
            color: var(--secondary);
            font-size: 1.8rem;
        }

        .profile-info p {
            margin: 0.5rem 0 0;
            color: #666;
        }

        .dashboard-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            transform: translateZ(10px);
            transition: var(--transition);
        }

        .dashboard-section:hover {
            transform: translateY(-5px) translateZ(20px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .section-title {
            color: var(--secondary);
            font-size: 1.5rem;
            margin-top: 0;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--light);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--primary);
        }

        .booking-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--primary);
            transition: var(--transition);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .booking-card:hover {
            transform: translateY(-3px) translateZ(10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .booking-info {
            flex: 1;
            min-width: 250px;
        }

        .booking-info h3 {
            margin: 0 0 0.5rem;
            color: var(--secondary);
        }

        .booking-dates {
            display: flex;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .booking-date {
            background: var(--light);
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .booking-date i {
            color: var(--primary);
        }

        .booking-status {
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            background: #eaf7ee;
            color: var(--success);
        }

        .booking-status.pending {
            background: #fff8e6;
            color: #f39c12;
        }

        .booking-status.cancelled {
            background: #fee;
            color: var(--accent);
        }

        .facility-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .facility-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: var(--transition);
            transform-style: preserve-3d;
        }

        .facility-card:hover {
            transform: translateY(-5px) rotateX(5deg);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .facility-img {
            height: 180px;
            overflow: hidden;
        }

        .facility-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .facility-card:hover .facility-img img {
            transform: scale(1.05);
        }

        .facility-content {
            padding: 1.5rem;
        }

        .facility-content h3 {
            margin: 0 0 0.5rem;
            color: var(--secondary);
        }

        .facility-content p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
            justify-content: center;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
            transform: translateY(-3px) translateZ(10px);
            box-shadow: 0 8px 20px rgba(41, 128, 185, 0.3);
        }

        .btn-danger {
            background: var(--accent);
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-3px) translateZ(10px);
            box-shadow: 0 8px 20px rgba(192, 57, 43, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            background: var(--light);
            border-radius: 10px;
            color: #666;
        }

        .empty-state i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .profile-header {
                flex-direction: column;
                text-align: center;
                padding: 1.5rem;
            }

            .profile-header::before {
                width: 100%;
                height: 8px;
                top: 0;
                left: 0;
            }

            .booking-card {
                flex-direction: column;
                align-items: flex-start;
            }

            .booking-dates {
                flex-direction: column;
                gap: 0.5rem;
            }

            .facility-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include('header.php') ?>

    <div class="dashboard-container">
        <!-- User Profile Section -->
        <div class="profile-header">
            <div class="profile-avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="profile-info">
                <h2><?= htmlspecialchars($user['name'] ?? 'User') ?></h2>
                <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($user['email'] ?? 'No email provided') ?></p>
                <p><i class="fas fa-phone"></i> <?= htmlspecialchars($user['phone'] ?? 'No phone provided') ?></p>
            </div>
        </div>

        <!-- Bookings Section -->
        <div class="dashboard-section">
            <h2 class="section-title"><i class="fas fa-calendar-check"></i> Your Bookings</h2>
            
            <?php if (count($bookings) > 0): ?>
                <?php foreach ($bookings as $booking): ?>
                    <div class="booking-card">
                        <div class="booking-info">
                            <h3><?= htmlspecialchars($booking['room_type']) ?></h3>
                            <div class="booking-dates">
                                <span class="booking-date">
                                    <i class="fas fa-sign-in-alt"></i> <?= htmlspecialchars($booking['checkin_date']) ?>
                                </span>
                                <span class="booking-date">
                                    <i class="fas fa-sign-out-alt"></i> <?= htmlspecialchars($booking['checkout_date']) ?>
                                </span>
                            </div>
                            <p><i class="fas fa-users"></i> <?= htmlspecialchars($booking['guests']) ?> guests</p>
                        </div>
                        <span class="booking-status <?= strtolower($booking['status']) ?>">
                            <?= htmlspecialchars($booking['status']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h3>No Bookings Found</h3>
                    <p>You haven't made any bookings yet.</p>
                    <a href="morerooms.php" class="btn btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-search"></i> Browse Rooms
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Facilities Section -->
        <div class="dashboard-section">
            <h2 class="section-title"><i class="fas fa-dumbbell"></i> Your Selected Facilities</h2>
            
            <?php if (count($facilities) > 0): ?>
                <div class="facility-container">
                    <?php foreach ($facilities as $facility): ?>
                        <div class="facility-card">
                            <div class="facility-img">
                                <img src="<?= htmlspecialchars($facility['facility_image']) ?>" alt="<?= htmlspecialchars($facility['facility_name']) ?>">
                            </div>
                            <div class="facility-content">
                                <h3><?= htmlspecialchars($facility['facility_name']) ?></h3>
                                <p><?= htmlspecialchars($facility['description']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-info-circle"></i>
                    <h3>No Facilities Selected</h3>
                    <p>You haven't selected any facilities yet.</p>
                    <a href="facility.php" class="btn btn-primary" style="margin-top: 1rem;">
                        <i class="fas fa-search"></i> Browse Facilities
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="morerooms.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Book a Room
            </a>
            <a href="logout.php" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</body>
</html>