<?php
$servername = "localhost";
$username = $_POST['login'];
$password = $_POST['pass'];
$dbname = "stock";

// echo "alert(".$username.")";

if (!$username)
{
    header('Location: /stock/index.php?message=notusername');
    die();
}

if (!$password)
{
    header('Location: /stock/index.php?message=notpass');
    die();
}

$conn = new mysqli($servername, $username, $password, $dbname);

if(!$conn)
{
    header('Location: /stock/index.php?message=notconnected');
    die();
}
else
{
    session_start();
    
    $_SESSION["servername"] = $servername;
    $_SESSION["username"] = $username;
    $_SESSION["passwd"] = $password;
    $_SESSION["dbname"] = $dbname;

    switch($username)
    {
        case 'admin':
            header('Location: /stock/admin/stock.php');
            break;
        case 'manager':
            header('Location: /stock/manager/stock.php');
            break;
        default:
            header('Location: /stock/stock_user/stock.php');
    }
    // if ($username != "admin" && $username != "manager")
        

    
}
?>