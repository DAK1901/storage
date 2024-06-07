<?php

session_start();
$servername = $_SESSION["servername"];
$username = $_SESSION["username"];
$password = $_SESSION["passwd"];
$dbname = $_SESSION["dbname"];

$conn = new mysqli($servername, $username, $password, $dbname);
// echo "dfcghjk";

$id = $_GET['id'];
// echo $id;
$coming = mysqli_query($conn, "SELECT product_id, count FROM expenses WHERE id = ".$id.";");
$row = mysqli_fetch_array($coming);
// echo "drftgyhjk";
// echo $row['count'];

mysqli_query($conn, "SELECT expense_func(".$row['product_id'].", ".$row['count'].");");
mysqli_query($conn, "UPDATE expenses 
                     SET done = 1
                     WHERE id = ".$id.";");

echo "Заказ успешно собран!";

?>