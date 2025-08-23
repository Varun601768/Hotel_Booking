<?php
include("../connection.php");

$id = $_GET['id'];
$sql = "DELETE FROM facilities WHERE facility_id=$id";
mysqli_query($conn, $sql);

header("Location: facility.php");
?>
