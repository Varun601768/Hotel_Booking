<?php
session_start();
include("../connection.php");

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

// Get facility ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch facility data
$stmt = $conn->prepare("SELECT * FROM facilities WHERE facility_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Facility not found");
}

// Handle form submission
if (isset($_POST['update'])) {
    $name = htmlspecialchars(trim($_POST['facility_name']));
    $desc = htmlspecialchars(trim($_POST['description']));

    // Handle file upload if new image is provided
    if (!empty($_FILES['facility_image']['name'])) {
        $filename = basename($_FILES['facility_image']['name']);
        $target_file = "uploads/" . $filename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check if file is an actual image
        $check = getimagesize($_FILES['facility_image']['tmp_name']);
        if ($check !== false) {
            if (move_uploaded_file($_FILES['facility_image']['tmp_name'], $target_file)) {
                $update_query = "UPDATE facilities SET facility_name = ?, description = ?, facility_image = ? WHERE facility_id = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("sssi", $name, $desc, $filename, $id);
            }
        } else {
            $error = "File is not an image.";
        }
    } else {
        $update_query = "UPDATE facilities SET facility_name = ?, description = ? WHERE facility_id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssi", $name, $desc, $id);
    }

    if ($stmt->execute()) {
        header("Location: facility.php");
        exit();
    } else {
        $error = "Error updating facility: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Facility</title>
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
            text-align: center;
        }
        
        .current-image img {
            max-width: 200px;
            height: auto;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .file-input {
            padding: 0.5rem;
            border: 1px dashed #ddd;
            border-radius: 5px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .file-input:hover {
            border-color: var(--primary-color);
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .submit-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
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
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
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

<?php include('admin_header.php');?>

<!-- Edit Facility Form -->
<div class="container">
    <div class="edit-form">
        <h2 class="form-title">Edit Facility</h2>
        
        <?php if(isset($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            
            <div class="form-group">
                <label for="facility_name">Facility Name:</label>
                <input type="text" id="facility_name" name="facility_name" 
                       value="<?= htmlspecialchars($row['facility_name']) ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required><?= 
                    htmlspecialchars($row['description']) ?></textarea>
            </div>
            
            <div class="form-group">
                <label>Current Image:</label>
                <div class="current-image">
                    <img src="uploads/<?= htmlspecialchars($row['facility_image']) ?>" 
                         alt="<?= htmlspecialchars($row['facility_name']) ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label for="facility_image">New Image (leave blank to keep current):</label>
                <div class="file-input">
                    <input type="file" id="facility_image" name="facility_image" accept="image/*">
                </div>
            </div>
            
            <button type="submit" name="update" class="submit-btn">Update Facility</button>
        </form>
    </div>
</div>

</body>
</html>