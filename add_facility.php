<?php 
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['facility_id'])) {
    $facility_id = intval($_POST['facility_id']);

    // Check if already booked
    $check_sql = "SELECT * FROM facility_bookings WHERE user_id = ? AND facility_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $facility_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('Facility already added to your dashboard!'); window.location.href='facility.php';</script>";
        exit();
    }
    $check_stmt->close();

    // Insert Booking
    $sql = "INSERT INTO facility_bookings (user_id, facility_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error in SQL statement: " . $conn->error);
    }

    $stmt->bind_param("ii", $user_id, $facility_id);

    if ($stmt->execute()) {
        echo "<script>alert('Facility added successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "<script>alert('Error adding facility!'); window.location.href='facility.php';</script>";
    }

    $stmt->close();
}
?>
