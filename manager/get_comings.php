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

$temp_table = mysqli_query($conn, "SELECT product_id, name, sum(count) as sum FROM coming LEFT JOIN products USING(product_id)
                                   WHERE date BETWEEN '".$from."' AND '".$to."' GROUP BY product_id ORDER BY sum DESC;");
while ($temp_table_row = mysqli_fetch_array($temp_table))
{
    echo "<tr>";
    echo "<td>".$temp_table_row['product_id']."</td>";
    echo "<td>".$temp_table_row['name']."</td>";
    echo "<td>".$temp_table_row['sum']."</td>";
    echo "</tr>";
}

?>