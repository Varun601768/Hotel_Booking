<?php
session_start();
include('../connection.php');

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$id = $_GET['id'];
$query = "SELECT * FROM rooms WHERE room_id=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if(isset($_POST['update'])){
    $name = htmlspecialchars($_POST['name']);
    $desc = htmlspecialchars($_POST['description']);
    $price = floatval($_POST['price']);

    if(!empty($_FILES['image']['name'])){
        $image = basename($_FILES['image']['name']);
        $target_file = "uploads/" . $image;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if image file is valid
        $check = getimagesize($_FILES['image']['tmp_name']);
        if($check !== false) {
            if(move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $update_query = "UPDATE rooms SET room_name=?, description=?, price=?, room_image=? WHERE room_id=?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("ssdsi", $name, $desc, $price, $image, $id);
            }
        }
    } else {
        $update_query = "UPDATE rooms SET room_name=?, description=?, price=? WHERE room_id=?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssdi", $name, $desc, $price, $id);
    }

    if($stmt->execute()) {
        header('location: rooms.php');
        exit();
    } else {
        $error = "Error updating room: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Room</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --shadow: 0 10px 20px rgba(0,0,0,0.2);
            --inset-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
            --box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: var(--dark-color);
        }
        
        nav {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem 2rem;
            box-shadow: var(--shadow);
            position: relative;
            z-index: 100;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        nav h2 {
            font-size: 1.8rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        nav ul {
            display: flex;
            list-style: none;
        }
        
        nav ul li {
            margin-left: 1.5rem;
        }
        
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            position: relative;
        }
        
        nav ul li a:hover {
            background-color: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }
        
        nav ul li a:active {
            transform: translateY(0);
        }
        
        .container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .edit-form {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: var(--shadow);
            transform-style: preserve-3d;
            transition: all 0.5s ease;
        }
        
        .edit-form:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        
        .edit-form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
        
        .form-title {
            color: var(--dark-color);
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: var(--inset-shadow);
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }
        
        .current-image {
            margin: 1rem 0;
        }
        
        .current-image img {
            max-width: 200px;
            height: auto;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .submit-btn {
            background: linear-gradient(135deg, var(--success-color), #27ae60);
            color: white;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            width: 100%;
        }
        
        .submit-btn:hover {
            background: linear-gradient(135deg, #27ae60, var(--success-color));
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .submit-btn:active {
            transform: translateY(0);
        }
        
        .error-message {
            color: var(--accent-color);
            text-align: center;
            margin-bottom: 1rem;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                padding: 1rem;
            }
            
            nav ul {
                margin-top: 1rem;
                flex-wrap: wrap;
                justify-content: center;
            }
            
            nav ul li {
                margin: 0.5rem;
            }
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<?php include('admin_header.php');?>

<!-- Edit Room Form -->
<div class="container">
    <div class="edit-form">
        <h2 class="form-title">Edit Room</h2>
        
        <?php if(isset($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Room Name:</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($data['room_name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?= htmlspecialchars($data['description']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" min="0" value="<?= htmlspecialchars($data['price']) ?>" required>
            </div>
            
            <div class="form-group">
                <label>Current Image:</label>
                <div class="current-image">
                    <?php if(!empty($data['room_image'])): ?>
                        <img src="uploads/<?= htmlspecialchars($data['room_image']) ?>" alt="Room Image">
                    <?php else: ?>
                        <p>No image uploaded</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="form-group">
                <label for="image">New Image (leave blank to keep current):</label>
                <input type="file" id="image" name="image" accept="image/*">
            </div>
            
            <button type="submit" name="update" class="submit-btn">Update Room</button>
        </form>
    </div>
</div>

</body>
</html>