<?php
include '../connection.php';

$id = $_GET['id'];
$sql = "DELETE FROM about_info WHERE id=$id";
mysqli_query($conn, $sql);

header('location:about.php');
?>
