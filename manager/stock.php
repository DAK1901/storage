<?php
session_start();
$servername = $_SESSION["servername"];
$username = $_SESSION["username"];
$password = $_SESSION["passwd"];
$dbname = $_SESSION["dbname"];

$conn = new mysqli($servername, $username, $password, $dbname);
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../fonts/fonts.css" />
    <link rel="stylesheet" href="../styles/styles.css"> 
    <title>Состав склада</title>
</head>
<body class="table-body">
    <header class="table-header">
        <a href="./stock.php" class="structure table-button">Состав склада</a>
        <a href="./coming.php" class="entry table-button">Поставки</a>
        <a href="./expenses.php" class="orders table-button">Заказы</a>
    </header>
    <main class="table-main">
        <div class="scroll-table">
            <table>
                <thead>
                    <tr>
                        <th>Идетификационный номер</th>
                        <th>Секция</th>
                        <th>Идетификационный номер товара</th>
                        <th>Название товара</th>
                        <th>Количество</th>
                    </tr>
                </thead>
            </table>	
            <div class="scroll-table-body">
                <table>
                    <tbody>
                        <?php
                            $stock = mysqli_query($conn, "SELECT id, section, storage.product_id, name, count FROM 
                                                          storage LEFT JOIN products ON storage.product_id = products.product_id;");
                            while ($row = mysqli_fetch_array($stock))
                            {
                                echo "<tr>";
                                echo"<td>".$row['id']."</td>";
                                echo"<td>".$row['section']."</td>";
                                echo"<td>".$row['product_id']."</td>";
                                echo"<td>".$row['name']."</td>";
                                echo"<td>".$row['count']."</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>	
        </div>
    </main>
</body>
</html>
