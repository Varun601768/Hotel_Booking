<?php
include '../connection.php';
$id = $_GET['id'];
$sql = "SELECT * FROM about_info WHERE id=$id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update About Info</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            padding: 30px 40px;
            width: 90%;
            max-width: 600px;
            transform-style: preserve-3d;
            transform: perspective(1000px) rotateX(1deg);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .form-container:hover {
            transform: perspective(1000px) rotateX(3deg) translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        }
        
        h1 {
            color: #3a4a6d;
            text-align: center;
            margin-bottom: 30px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
            font-size: 28px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #3a4a6d;
            font-size: 16px;
        }
        
        input[type="text"],
        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e4ed;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
            box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);
            background-color: #f8f9fc;
        }
        
        input[type="text"]:focus,
        textarea:focus {
            border-color: #5c7cfa;
            box-shadow: 0 0 0 3px rgba(92, 124, 250, 0.2);
            outline: none;
            background-color: white;
        }
        
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .file-input-container {
            position: relative;
            margin-bottom: 25px;
        }
        
        .file-input-label {
            display: block;
            padding: 12px 15px;
            background: linear-gradient(to right, #f8f9fc, #e0e4ed);
            border: 2px dashed #c3cfe2;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            color: #5c7cfa;
            font-weight: 600;
        }
        
        .file-input-label:hover {
            background: linear-gradient(to right, #e0e4ed, #d0d8e8);
            border-color: #5c7cfa;
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
            background: linear-gradient(to right, #5c7cfa, #3a4a6d);
            color: white;
            border: none;
            padding: 14px 25px;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(92, 124, 250, 0.3);
            width: 100%;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(92, 124, 250, 0.4);
        }
        
        .submit-btn:active {
            transform: translateY(1px);
        }
        
        .status-message {
            margin-top: 20px;
            padding: 12px;
            border-radius: 8px;
            background-color: #d4edda;
            color: #155724;
            text-align: center;
            display: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Update About Information</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($row['title']); ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description"><?php echo htmlspecialchars($row['description']); ?></textarea>
            </div>
            
            <div class="file-input-container">
                <label for="video" class="file-input-label">
                    <?php echo $row['video'] ? 'Change Video File' : 'Upload Video File'; ?>
                    <span id="file-name"><?php echo $row['video'] ? "(Current: ".htmlspecialchars($row['video']).")" : ""; ?></span>
                </label>
                <input type="file" name="video" id="video">
            </div>
            
            <input type="submit" name="update" value="Update" class="submit-btn">
        </form>
        
        <?php if(isset($_POST['update'])): ?>
        <div class="status-message" style="display: block;">
            About Info Updated Successfully!
        </div>
        <?php endif; ?>
    </div>

    <script>
        // Update file name display when a file is selected
        document.getElementById('video').addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';
            document.getElementById('file-name').textContent = fileName ? ` (${fileName})` : '';
        });
    </script>
</body>
</html>

<?php
if(isset($_POST['update'])){
    $title = $_POST['title'];
    $desc = $_POST['description'];

    if($_FILES['video']['name'] != ''){
        $video = $_FILES['video']['name'];
        $tmp = $_FILES['video']['tmp_name'];
        move_uploaded_file($tmp, "upload/".$video);
    } else {
        $video = $row['video'];
    }

    $sql = "UPDATE about_info SET title='$title', description='$desc', video='$video' WHERE id=$id";
    mysqli_query($conn,$sql);
}
?>