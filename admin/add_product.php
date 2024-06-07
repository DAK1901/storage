<?php

// echo "fghjk";
session_start();
$servername = $_SESSION["servername"];
$username = $_SESSION["username"];
$password = $_SESSION["passwd"];
$dbname = $_SESSION["dbname"];

$conn = new mysqli($servername, $username, $password, $dbname);

$prod_name = $_GET['name'];
$prod_weight = $_GET['weight'];
$prod_volume = $_GET['volume'];

// echo($from);
// echo($to);

if(mysqli_query($conn, "INSERT INTO products (name, weight, volume) VALUES ('".$prod_name."',".$prod_weight.",".$prod_volume.");"))
{
    echo "Товар успешно добалвлен";
}
else
{
    echo "Ошибка";
}

?>