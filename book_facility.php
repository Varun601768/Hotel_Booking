<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    echo "Please log in to book a facility.";
    exit();
}

$user_id = $_SESSION['user_id'];
$facility_id = isset($_POST['facility_id']) ? intval($_POST['facility_id']) : 0;

// Check if facility_id is valid
if ($facility_id <= 0) {
    echo "Invalid facility selection.";
    exit();
}

// Insert into facility_bookings table
$query = "INSERT INTO facility_bookings (user_id, facility_id, booking_id) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Error in SQL: " . $conn->error);
}

// Assuming booking_id is required, use a valid ID (or modify the schema if optional)
$booking_id = 0;  // You might need to fetch a real booking ID if required

$stmt->bind_param("iii", $user_id, $facility_id, $booking_id);
if ($stmt->execute()) {
    echo "Facility booked successfully!";
} else {
    echo "Error booking facility.";
}

$stmt->close();
$conn->close();
?>
