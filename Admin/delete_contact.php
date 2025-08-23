<?php
include("../connection.php");

if(isset($_GET['id'])){
    $id = $_GET['id'];

    $sql = "DELETE FROM contact_messages WHERE id = $id";

    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Message deleted successfully');window.location.href='contact_us.php';</script>";
    } else {
        echo "Failed to delete";
    }
} else {
    header("Location: contact_us.php");
}
?>
