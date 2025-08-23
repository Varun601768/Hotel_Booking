<?php
include '../connection.php';

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp_name, "upload/".$image);

    $sql = "INSERT INTO management_team(name, image) VALUES('$name',  '$image')";
    mysqli_query($conn, $sql);
    header("Location: about.php");
}
?>

<form method="post" enctype="multipart/form-data">
    Name: <input type="text" name="name" required><br>
    Image: <input type="file" name="image" required><br>
    <input type="submit" name="submit" value="Add">
</form>
