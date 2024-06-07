<?php

// echo "fghjk";
session_start();
$servername = $_SESSION["servername"];
$username = $_SESSION["username"];
$password = $_SESSION["passwd"];
$dbname = $_SESSION["dbname"];

$conn = new mysqli($servername, $username, $password, $dbname);

$from = $_GET['from'];
$to = $_GET['to'];

// echo($from);
// echo($to);

$temp_table = mysqli_query($conn, "SELECT * FROM coming WHERE date BETWEEN '".$from."' AND '".$to."';");
while ($temp_table_row = mysqli_fetch_array($temp_table))
{
    echo "<tr>";
    echo "<td>".$temp_table_row['id']."</td>";
    echo "<td>".$temp_table_row['date']."</td>";
    echo "<td>".$temp_table_row['product_id']."</td>";
    echo "<td>".$temp_table_row['count']."</td>";
    if ($temp_table_row['unloading'] == 1)
        echo "<td>Выгружен</td>";
    else
        echo "<td>Ожидает выгрузки</td>";
    echo "</tr>";
}


?>