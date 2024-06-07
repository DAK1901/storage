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

$temp_table = mysqli_query($conn, "CALL coming_proc_table(".$row['product_id'].", ".$row['count'].");");
while ($temp_table_row = mysqli_fetch_array($temp_table))
{
    echo "<tr>";
    echo "<td>".$temp_table_row['section']."</td>";
    echo "<td>".$temp_table_row['count']."</td>";
    echo "<td>".$temp_table_row['weight']."</td>";
    echo "</tr>";
}
?>