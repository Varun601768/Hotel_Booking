<?php
include '../connection.php';

$id = $_GET['id'];
$sql = "SELECT * FROM management_team WHERE id='$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update'])){
    $name = $_POST['name'];

    if($_FILES['image']['name'] != ''){
        $image = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        move_uploaded_file($tmp_name, "upload/".$image);
    }else{
        $image = $row['image'];
    }

    $sql = "UPDATE management_team SET name='$name', image='$image' WHERE id='$id'";
    mysqli_query($conn, $sql);
    header("Location: about.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Team Member</title>
    <style>
        :root {
            --primary-color: #4a6bff;
            --secondary-color: #3a4a6d;
            --light-bg: #f8f9fc;
            --border-color: #e0e4ed;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            padding: 30px 40px;
            width: 90%;
            max-width: 500px;
            transform-style: preserve-3d;
            transform: perspective(1000px) rotateY(1deg);
            transition: all 0.4s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .form-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
        }
        
        .form-container:hover {
            transform: perspective(1000px) rotateY(3deg) translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        }
        
        h1 {
            color: var(--secondary-color);
            text-align: center;
            margin-bottom: 30px;
            font-size: 24px;
            position: relative;
            padding-bottom: 10px;
        }
        
        h1::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 50px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 3px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--secondary-color);
            font-size: 14px;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--border-color);
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s;
            background-color: var(--light-bg);
        }
        
        input[type="text"]:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 107, 255, 0.2);
            outline: none;
            background-color: white;
        }
        
        .image-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 20px 0;
        }
        
        .current-image {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 5px 15px var(--shadow-color);
            margin-bottom: 15px;
        }
        
        .file-input-container {
            position: relative;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .file-input-label {
            display: inline-block;
            padding: 12px 25px;
            background: linear-gradient(to right, var(--light-bg), #e0e4ed);
            border: 2px dashed #c3cfe2;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 14px;
        }
        
        .file-input-label:hover {
            background: linear-gradient(to right, #e0e4ed, #d0d8e8);
            border-color: var(--primary-color);
        }
        
        input[type="file"] {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }
        
        .submit-btn {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 14px 25px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(74, 107, 255, 0.3);
            width: 100%;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(74, 107, 255, 0.4);
        }
        
        .submit-btn:active {
            transform: translateY(1px);
        }
        
        .file-name {
            display: block;
            margin-top: 8px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Update Team Member</h1>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
            </div>
            
            <div class="image-preview">
                <label>Current Image:</label>
                <img src="upload/<?php echo htmlspecialchars($row['image']); ?>" class="current-image" alt="Current Image">
            </div>
            
            <div class="file-input-container">
                <label for="image" class="file-input-label">
                    Change Image
                </label>
                <input type="file" name="image" id="image" accept="image/*">
                <span class="file-name" id="file-name">No file selected</span>
            </div>
            
            <input type="submit" name="update" value="Update" class="submit-btn">
        </form>
    </div>

    <script>
        // Update file name display when a file is selected
        document.getElementById('image').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';
            document.getElementById('file-name').textContent = fileName;
            
            // Preview the new image
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.querySelector('.current-image').src = event.target.result;
                };
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    </script>
</body>
</html>