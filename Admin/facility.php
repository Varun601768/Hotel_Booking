<?php
session_start();
include("../connection.php");

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$search = isset($_GET['search']) ? htmlspecialchars(trim($_GET['search'])) : '';

// Prepare statement to prevent SQL injection
$sql = "SELECT * FROM facilities WHERE facility_name LIKE CONCAT('%', ?, '%')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $search);
$stmt->execute();
$result = $stmt->get_result();

// Check for query errors
if (!$result) {
    die("Error fetching facilities: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Facilities</title>
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
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .search-container {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .search-form {
            display: flex;
            flex: 1;
            min-width: 300px;
            box-shadow: var(--shadow);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .search-form input {
            flex: 1;
            padding: 0.8rem 1rem;
            border: none;
            font-size: 1rem;
            outline: none;
        }
        
        .search-form button {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0 1.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .search-form button:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }
        
        .add-btn {
            display: inline-block;
            background: linear-gradient(135deg, var(--success-color), #27ae60);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            white-space: nowrap;
        }
        
        .add-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }
        
        .facility-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }
        
        .facility-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .facility-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        .facility-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }
        
        .facility-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }
        
        .facility-content {
            padding: 1.5rem;
        }
        
        .facility-content h3 {
            color: var(--dark-color);
            margin-bottom: 0.5rem;
            font-size: 1.3rem;
        }
        
        .facility-content p {
            color: #666;
            margin-bottom: 1.5rem;
            line-height: 1.5;
        }
        
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .edit-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            flex: 1;
            text-align: center;
        }
        
        .delete-btn {
            background: linear-gradient(135deg, var(--accent-color), #c0392b);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            flex: 1;
            text-align: center;
        }
        
        .action-buttons a:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
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
            
            .search-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .search-form {
                min-width: 100%;
            }
            
            .facility-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- Navigation Bar -->
<?php include('admin_header.php');?>

<!-- Facilities Management -->
<div class="container">
    <div class="search-container">
        <form method="GET" action="facility.php" class="search-form">
            <input type="text" name="search" placeholder="Search facility..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
        </form>
        <a href="add_facility.php" class="add-btn">Add Facility</a>
    </div>

    <div class="facility-container">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="facility-card">
                <img src="<?= htmlspecialchars($row['facility_image']) ?>" alt="<?= htmlspecialchars($row['facility_name']) ?>">
                <div class="facility-content">
                    <h3><?= htmlspecialchars($row['facility_name']) ?></h3>
                    <p><?= htmlspecialchars($row['description']) ?></p>
                    <div class="action-buttons">
                        <a href="edit_facility.php?id=<?= htmlspecialchars($row['facility_id']) ?>" class="edit-btn">Edit</a>
                        <a href="delete_facility.php?id=<?= htmlspecialchars($row['facility_id']) ?>" onclick="return confirm('Are you sure you want to delete this facility?')" class="delete-btn">Delete</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

</body>
</html>