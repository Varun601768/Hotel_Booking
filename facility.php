<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all facilities
$sql = "SELECT * FROM facilities";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facilities</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .facility-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }
        .facility-card {
            width: 300px;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 15px;
        }
        .facility-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }
        .add-btn {
            display: block;
            background: #28a745;
            color: white;
            padding: 10px;
            margin-top: 10px;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .add-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>
<?php include('header.php'); ?>

    <h2>Facilities</h2>

    <div class="facility-container">
        <?php while ($facility = $result->fetch_assoc()): ?>
            <div class="facility-card">
                <img src="<?= htmlspecialchars($facility['facility_image']); ?>" alt="<?= htmlspecialchars($facility['facility_name']); ?>">
                <h3><?= htmlspecialchars($facility['facility_name']); ?></h3>
                <p><?= htmlspecialchars($facility['description']); ?></p>
                
                <!-- Add to Dashboard Button -->
                <form action="add_facility.php" method="POST">
                    <input type="hidden" name="facility_id" value="<?= $facility['facility_id']; ?>">
                    <button type="submit" class="add-btn">Add to Dashboard</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>
