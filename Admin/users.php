<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

include('../connection.php');

// Search functionality
$search_query = "";
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

// Fetch all users or filter by search query
$sql = "SELECT * FROM users WHERE full_name LIKE '%$search_query%' OR email LIKE '%$search_query%'";
$result = $conn->query($sql);

// Check for query errors
if (!$result) {
    die("Error fetching users: " . $conn->error);
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    if ($conn->query("DELETE FROM users WHERE user_id=$delete_id") === TRUE) {
        header("Location: users.php");
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}

if (isset($_POST['update_status'])) {
    $user_id = $_POST['user_id'];
    $new_status = $_POST['status'];
    if ($conn->query("UPDATE users SET status='$new_status' WHERE user_id=$user_id") === TRUE) {
        header("Location: users.php");
    } else {
        echo "Error updating status: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
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
        
        h1 {
            color: var(--dark-color);
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 2.2rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
        }
        
        form[method="GET"] {
            display: flex;
            margin-bottom: 2rem;
            box-shadow: var(--shadow);
            border-radius: 8px;
            overflow: hidden;
        }
        
        form[method="GET"] input {
            flex: 1;
            padding: 0.8rem 1rem;
            border: none;
            font-size: 1rem;
            outline: none;
        }
        
        form[method="GET"] button {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 0 1.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        form[method="GET"] button:hover {
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
        }
        
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
            margin-bottom: 2rem;
        }
        
        table th {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        table td {
            padding: 1rem;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        table tr:last-child td {
            border-bottom: none;
        }
        
        table tr:hover td {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        select {
            padding: 0.5rem;
            border-radius: 4px;
            border: 1px solid #ddd;
            outline: none;
            background-color: white;
            box-shadow: var(--inset-shadow);
            transition: all 0.3s ease;
        }
        
        select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        button {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        button[type="submit"] {
            background: linear-gradient(135deg, var(--success-color), #27ae60);
            color: white;
        }
        
        button[type="submit"]:hover {
            background: linear-gradient(135deg, #27ae60, var(--success-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        button[type="submit"]:active {
            transform: translateY(0);
        }
        
        a button {
            background: linear-gradient(135deg, var(--accent-color), #c0392b);
            color: white;
        }
        
        a button:hover {
            background: linear-gradient(135deg, #c0392b, var(--accent-color));
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        
        a button:active {
            transform: translateY(0);
        }
        
        .status-pending {
            color: var(--accent-color);
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(231, 76, 60, 0.2);
        }
        
        .status-approved {
            color: var(--success-color);
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(46, 204, 113, 0.2);
        }
        
        .action-form {
            display: flex;
            gap: 0.5rem;
            align-items: center;
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
            
            table {
                display: block;
                overflow-x: auto;
            }
            
            .action-form {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>

<?php include('admin_header.php');?>

<!-- User Management -->
<div class="container">
    <h1>Manage Users</h1>

    <!-- Search Bar -->
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by name or email" value="<?php echo htmlspecialchars($search_query); ?>">
        <button type="submit">Search</button>
    </form>

    <!-- Table to Display Users -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td>
                        <span class="status-<?php echo strtolower($row['status']); ?>">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-form">
                            <form method="POST" action="">
                                <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($row['user_id']); ?>">
                                <select name="status">
                                    <option value="Pending" <?php if ($row['status'] == 'Pending') echo "selected"; ?>>Pending</option>
                                    <option value="Approved" <?php if ($row['status'] == 'Approved') echo "selected"; ?>>Approved</option>
                                </select>
                                <button type="submit" name="update_status">Update</button>
                            </form>
                            <a href="users.php?delete_id=<?php echo htmlspecialchars($row['user_id']); ?>" onclick="return confirm('Are you sure you want to delete this user?');">
                                <button>Delete</button>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

</body>
</html>