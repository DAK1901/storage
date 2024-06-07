<?php

session_start();
$servername = $_SESSION["servername"];
$username = $_SESSION["username"];
$password = $_SESSION["passwd"];
$dbname = $_SESSION["dbname"];

$conn = new mysqli($servername, $username, $password, $dbname);
// echo "dfcghjk";

$id = $_GET['id'];
// echo "ffffffffffff";
$coming = mysqli_query($conn, "SELECT product_id, count FROM coming WHERE id = ".$id.";");
$row = mysqli_fetch_array($coming);
// echo "drftgyhjk";
// echo $row['count'];

mysqli_query($conn, "CALL coming_proc(".$row['product_id'].", ".$row['count'].");");
mysqli_query($conn, "UPDATE coming 
                     SET unloading = 1
                     WHERE id = ".$id.";");

echo "Поставка успешно выгружена на склад!";

?>