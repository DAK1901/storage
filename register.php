<?php
$servername = "localhost";
$root = "root";
$rootpass = "12345";
$dbname = "stock";

$username = $_POST['login'];
$password = $_POST['pass'];
$true_pass = $_POST['true-pass'];

if ($password != $true_pass)
{
    header('Location: /stock/index.php?message=passnoteq');
    exit();
}

if (!$username)
{
    header('Location: /stock/index.php?message=notusername');
    exit();
}

if (!$password)
{
    header('Location: /stock/index.php?message=notpass');
    exit();
}

$conn = new mysqli($servername, $root, $rootpass, $dbname);
if($conn)
{
    $all_users = mysqli_query($conn, "SELECT user FROM mysql.user;");
    while ($row = mysqli_fetch_array($all_users))
    {
        if ($row['user'] == $username)
        {
            header('Location: /stock/index.php?message=isregistered');
            exit();
        }
    }
    mysqli_query($conn, "CREATE USER '".$username."'@'localhost' IDENTIFIED BY '".$password."';");
    mysqli_query($conn, "GRANT 'stockuser'@'localhost' TO  '".$username."'@'localhost';");
    mysqli_query($conn, "SET DEFAULT ROLE ALL TO '".$username."'@'localhost';");
    mysqli_query($conn, "FLUSH PRIVILEGES;");
}

header('Location: /stock/index.php?message=success');
?>